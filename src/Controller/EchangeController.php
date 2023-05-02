<?php

namespace App\Controller;

use App\Entity\Echange;
use App\Entity\Notification;
use App\Entity\Product;
use App\Form\EchangeType;
use App\Repository\EchangeRepository;
use App\Repository\ProductRepository;
use App\Services\TwilioService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/echange')]
class EchangeController extends AbstractController
{
    /*#[Route('/', name: 'app_echange_index', methods: ['GET'])]
    public function index(EchangeRepository $echangeRepository): Response
    {
        return $this->render('echange/index.html.twig', [
            'echanges' => $echangeRepository->findAll(),
        ]);
    }*/

    #[Route('/', name: 'app_echange_index', methods: ['GET', 'POST'])]
    public function index(Request $request, EntityManagerInterface $entityManager,ProductRepository $productRepository): Response
    {
        $user = $this->getUser();
        // Create a new instance of the Echange class
        $echange = new Echange();
        $myProducts = $productRepository->findByUser($user);


// Retrieve the products that do not belong to the user
        $products = $entityManager->getRepository(Product::class)
            ->createQueryBuilder('p')
            ->where('p.user != :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
        // Create the Echange form
        $form = $this->createForm(EchangeType::class, $echange, ['my_products' => $myProducts ,'products' =>$products]);
        // Handle the form submission
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Set the remaining fields of the Echange object
            $echange->setProduitEchange($form->get('produit_echange')->getData());
            $echange->setProduitOffert($form->get('produit_offert')->getData());
            $echange->setLieuEchange('Random location 1');
            $echange->setLieuOffre('Random location 2');
            $echange->setStatut('en attente');
            $echange->setLivreur(null);

            // Save the Echange object to the database
            $entityManager->persist($echange);
            $entityManager->flush();

            // Get the user who owns the product to exchange
            $productOwner = $echange->getProduitEchange()->getUser();

            // Create a new notification for the product owner
            $notification = new Notification();
            $notification->setTitle('Exchange request.');
            $notification->setStatus('not read');
            $notification->setContent('You have a new exchange request. ID'.$echange->getId());
            $notification->setUser($productOwner);
            $notification->setCreatedAt(new \DateTime());
            $notification->setEchange($echange);
            // Save the notification to the database
            $entityManager->persist($notification);
            $entityManager->flush();

            // Redirect to the same page to clear the form
            return $this->redirectToRoute('app_echange_index');
        }



        return $this->render('echange/index.html.twig', [
            'products' => $products,
            'form' => $form->createView(),
            'myProducts' => $myProducts,

        ]);
    }



    /**
     * @Route("/{id}", name="echange_show")
     */
    public function show($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $echange = $entityManager->getRepository(Echange::class)->find($id);

        if (!$echange) {
            throw $this->createNotFoundException('Exchange not found for ID '.$id);
        }



        return $this->render('echange/show.html.twig', [
            'echange' => $echange,
        ]);
    }

    /**
     * @Route("/{id}/accept", name="accept_echange")
     */
    public function acceptEchange(MailerInterface $mailer,Echange $echange): Response
    {
        // Set the echange's status to "en cours"
        $echange->setStatut('en cours');

        // Update the echange in the database
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($echange);
        $entityManager->flush();
        $emailContent = $this->renderView('echange/accept_email.html.twig', [
            'user1'=>$echange->getProduitEchange()->getUser(),
            'produitEchange'=>$echange->getProduitEchange(),
            'produitOffert'=>$echange->getProduitOffert(),
            'echange' => $echange,
            // other dynamic data as needed
        ]);
        $email = (new Email())
            ->from('roubafikacontact@gmail.com')
            ->to($echange->getProduitOffert()->getUser()->getEmail())
            ->subject('Test email')
            ->html($emailContent);



        $mailer->send($email);


        // Redirect to the echange details page
        return $this->redirectToRoute('front_mesEchange_show');
    }

    public function pdfExport(Pdf $snappy,Echange $echange) :Response {
        $html = $this->renderView('echange/_pdf.html.twig', [
            'echange' => $echange,

        ]);
        $snappy->setOption('enable-local-file-access', true);
        $snappy->setOption('orientation','landscape');
        $pdf = $snappy->getOutputFromHtml($html);

        return new PdfResponse(
            $pdf,
            'echange.pdf'
        );
    }


    public function mesEchanges(EchangeRepository $echangeRepository): Response
    {
        $user = $this->getUser();
        $echanges = $echangeRepository->createQueryBuilder('e')
            ->where('e.produit_echange IN (:products) OR e.produit_offert IN (:products)')
            ->setParameter('products', $user->getProducts())
            ->getQuery()
            ->getResult();

        return $this->render('echange/mesEchanges.html.twig', [
            'echanges' => $echanges,
        ]);
    }


    /**
     * @Route("/echange/{id}/refuse", name="refuse_echange")
     */
    public function refuseEchange(MailerInterface $mailer,Request $request, Echange $echange): Response
    {
        // Check if the connected user is the owner of one of the products to exchange or the product offered
        $user = $this->getUser();
        if ($echange->getProduitEchange()->getUser() !== $user && $echange->getProduitOffert()->getUser() !== $user) {
            throw $this->createAccessDeniedException('Access Denied.');
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($echange);
        $entityManager->flush();
        $emailContent = $this->renderView('echange/refuse_email.html.twig', [
            'user1'=>$echange->getProduitEchange()->getUser(),
            'produitEchange'=>$echange->getProduitEchange(),
            'produitOffert'=>$echange->getProduitOffert(),
            'echange' => $echange,
            // other dynamic data as needed
        ]);


        $email = (new Email())
            ->from('roubafikacontact@gmail.com')
            ->to($echange->getProduitOffert()->getUser()->getEmail())
            ->subject('Test email')
            ->html($emailContent);


        $mailer->send($email);



        $this->addFlash('success', 'L\'échange a été refusé avec succès.');

        return $this->redirectToRoute('front_mesEchange_show');
    }



    #[Route('/{id}/edit', name: 'app_echange_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Echange $echange, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EchangeType::class, $echange);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_echange_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('echange/edit.html.twig', [
            'echange' => $echange,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_echange_delete', methods: ['POST'])]
    public function delete(Request $request, Echange $echange, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$echange->getId(), $request->request->get('_token'))) {
            $entityManager->remove($echange);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_echange_index', [], Response::HTTP_SEE_OTHER);
    }



}
