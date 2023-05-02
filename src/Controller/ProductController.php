<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Product;
use App\Entity\Reclamation;
use App\Entity\Service;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Repository\ReclamationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Snappy\Pdf;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/product')]
class ProductController extends AbstractController
{
    #[Route('/', name: 'app_product_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_product_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProductRepository $productRepository): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $imageFile = $form->get('imageFile')->getData();

            // Check if an image file has been uploaded
            if ($imageFile) {
                // Generate a unique name for the file before saving it
                $fileName = md5(uniqid()) . '.' . $imageFile->guessExtension();

                // Move the file to the directory where images are stored
                $imageFile->move(
                    $this->getParameter('images_directory'),
                    $fileName
                );

                // Update the 'image' property of the product entity
                $product->setImageName($fileName);
            }
            $productRepository->save($product, true);

            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_product_show', methods: ['GET'])]
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_product_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Product $product, ProductRepository $productRepository): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productRepository->save($product, true);

            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_product_delete', methods: ['POST'])]
    public function delete(Request $request, Product $product, ProductRepository $productRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $productRepository->remove($product, true);
        }

        return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/product/add-to-basket/{id}", name="app_product_add_to_basket")
     */
    public function addToBasket(Product $produit, SessionInterface $session): Response
    {
        $panier = $session->get('panier', []);

        if (!array_key_exists($produit->getId(), $panier)) {
            $panier[$produit->getId()] = [
                'id' => $produit->getId(),
                'nomProduit' => $produit->getNomProduit(),
                'prix' => $produit->getPrice(),
                'quantite' => 1,
            ];
        } else {
            $panier[$produit->getId()]['quantite']++;
        }

        $session->set('panier', $panier);

        return $this->redirectToRoute('app_product_index');
    }

    /**
     * @Route("/product/{id}/command", name="app_product_command", methods={"POST"})
     */
    public function command(Request $request, Product $product, SessionInterface $session): Response
    {
        // Logic to add the product to the user's orders table or basket
        // ...

        return $this->redirectToRoute('app_product_index');
    }

    #[Route('/command/{id}', name: 'app_product_command')]
    public function commandProduct(Product $product, EntityManagerInterface $entityManager,Request $request): Response
    {
        $user = $this->getUser(); // replace this with your own user retrieval logic

        // create a new Commande object and set its fields
        $commande = new Commande();
        $commande->setDate(new \DateTime());
        $commande->setUser($user);
        $commande->setTotal($product->getPrice());

        // add the clicked product to the Commande object
        $commande->addProduit($product);

        // save the new Commande object to the database
        $entityManager->persist($commande);
        $entityManager->flush();

        return new RedirectResponse($request->headers->get('referer'));
    }

    public function frontList(Request $request,ProductRepository $productRepository,PaginatorInterface $paginator): Response
    {
        $products = $productRepository
            ->createQueryBuilder('p')
            ->where('p.user != :user')
            ->setParameter('user', $this->getUser())
            ->getQuery()
            ->getResult();
        $pagination = $paginator->paginate(
            $products,
            $request->query->getInt('page', 1), // Get the page parameter from the request (default to 1)
            3 // Items per page
        );

        return $this->render('product/frontList.html.twig', [
            'pagination' => $pagination,
        ]);

    }

    public function createProduct(FlashyNotifier $flashy ,Request $request, ProductRepository $productRepository): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $imageFile = $form->get('imageFile')->getData();

            // Check if an image file has been uploaded
            if ($imageFile) {
                // Generate a unique name for the file before saving it
                $fileName = md5(uniqid()) . '.' . $imageFile->guessExtension();

                // Move the file to the directory where images are stored
                $imageFile->move(
                    $this->getParameter('images_directory'),
                    $fileName
                );

                // Update the 'image' property of the product entity
                $product->setImageName($fileName);
            }
            $product->setUser($this->getUser());
            $productRepository->save($product, true);
            $flashy->success('Votre produit a été enregistrée.','http://localhost:8000/');
            return $this->redirectToRoute('front_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product/createProduct.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    public function myfrontList(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findByUser($this->getUser());

        return $this->render('product/myfrontList.html.twig', [
            'products' => $products,
        ]);
    }

    public function frontaddToBasket(Product $produit, SessionInterface $session): Response
    {
        $panier = $session->get('panier', []);

        if (!array_key_exists($produit->getId(), $panier)) {
            $panier[$produit->getId()] = [
                'id' => $produit->getId(),
                'nomProduit' => $produit->getNomProduit(),
                'prix' => $produit->getPrice(),
                'quantite' => 1,
            ];
        } else {
            $panier[$produit->getId()]['quantite']++;
        }

        $session->set('panier', $panier);

        return $this->redirectToRoute('front_product_index');
    }

    public function deleteProduct(Request $request, EntityManagerInterface $entityManager)
    {
        $id = $request->get('id');

        $productRepository = $entityManager->getRepository(Product::class);
        $product = $productRepository->find($id);

        if (!$product) {
            throw $this->createNotFoundException('Product not found');
        }

        $entityManager->remove($product);
        $entityManager->flush();

        return new RedirectResponse($request->headers->get('referer'));
    }

    public function frontshow(Product $produit): Response
    {
        Stripe::setApiKey('sk_test_8TNB5HaJ0H5lWP5qMso3OWDI00syLPhFY3');

        // create a new Stripe checkout session
        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd', // replace with your currency
                    'product_data' => [
                        'name' => $produit->getNomProduit(),
                    ],
                    'unit_amount' => $produit->getPrice() * 100, // Stripe requires the amount in cents
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => 'http://localhost:8000/product/payment/success/'.$produit->getId().'/{CHECKOUT_SESSION_ID}',
            'cancel_url' => 'http://localhost:8000/product/payment/cancel/'.$produit->getId(),

        ]);
        return $this->render('product/frontshow.html.twig', [
            'produit' => $produit,
            'CHECKOUT_SESSION_ID' =>$session->id
        ]);
    }

    public function frontcommandProduct(Product $product, EntityManagerInterface $entityManager, Request $request): Response
    {
        $user = $this->getUser(); // replace this with your own user retrieval logic
        $onlinePayment = false;

        $paymentMethod = $request->request->get('paymentMethod');
        if ($paymentMethod === 'online') {
            $onlinePayment = true;
            Stripe::setApiKey('sk_test_8TNB5HaJ0H5lWP5qMso3OWDI00syLPhFY3');

                // create a new Stripe checkout session
                $session = Session::create([
                    'payment_method_types' => ['card'],
                    'line_items' => [[
                        'price_data' => [
                            'currency' => 'usd', // replace with your currency
                            'product_data' => [
                                'name' => $product->getNomProduit(),
                            ],
                            'unit_amount' => $product->getPrice() * 100, // Stripe requires the amount in cents
                        ],
                        'quantity' => 1,
                    ]],
                    'mode' => 'payment',
                    'success_url' => 'http://localhost:8000/product/payment/success/'.$product->getId().'/{CHECKOUT_SESSION_ID}',
                    'cancel_url' => 'http://localhost:8000/product/payment/cancel/'.$product->getId(),
                ]);
                // redirect to the Stripe checkout page

            return new RedirectResponse($session->url, 303);


        } else {
            // create a new Commande object and set its fields
            $commande = new Commande();
            $commande->setDate(new \DateTime());
            $commande->setUser($user);
            $commande->setTotal($product->getPrice());
            $commande->setPaymentMethod('cash'); // set the payment method

            // add the clicked product to the Commande object
            $commande->addProduit($product);


            // save the new Commande object to the database
            $entityManager->persist($commande);
            $entityManager->flush();
// redirect back to the service page
            return $this->render('commande/frontshow.html.twig', [
                'commande' => $commande,
                'onlinePayment' => $onlinePayment,
            ]);
        }
    }

    public function paymentSuccess(EntityManagerInterface $entityManager,Product $product)
    {
        // create a new Commande object and set its fields
        $commande = new Commande();
        $commande->setDate(new \DateTime());
        $commande->setUser($this->getUser());
        $commande->setTotal($product->getPrice());
        $commande->setPaymentMethod("online");

        // add the clicked product to the Commande object
        $commande->addProduit($product);

        // save the new Commande object to the database
        $entityManager->persist($commande);
        $entityManager->flush();
        // Render the payment success page and generate a PDF version of the order confirmation

        // Render the payment success page
        return $this->render('product/payment_success.html.twig', [
            'product' => $product
        ]);
    }

    public function paymentCancel(Product $product)
    {
        // Render the payment cancel page
        return $this->render('product/payment_cancel.html.twig', [
            'product' => $product
        ]);
    }

    #[Route('/produit/{id}/reclamer/{description}', name: 'front_reclamer_produit')]
    public function reclamerProduit(FlashyNotifier $flashy,HubInterface $hub,$description,Product $produit, Request $request,ReclamationRepository $reclamationRepository): RedirectResponse
    {

        $reclamation = new Reclamation();
        $reclamation->setUser($this->getUser())
            ->setSujet("[Produit] : ".$produit->getNomProduit())
            ->setDescription($description)
            ->setDateAjout(new \DateTime());

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($reclamation);
        $entityManager->flush();



        $update = new Update(
            '/push',
            json_encode(['reclamation' => $reclamation,'user' => $this->getUser(),'produit'=>$produit])
        );
        $hub->publish($update);

        $flashy->success('Votre reclamation a été enregistrée.','http://localhost:8000/');
        // redirect back to the precedent page
        return new RedirectResponse($request->headers->get('referer'));
    }
}
