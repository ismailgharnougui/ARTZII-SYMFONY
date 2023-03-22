<?php

namespace App\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\User;
use App\Entity\Basket;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use App\Repository\ArticlesRepository;
use App\Repository\BasketRepository;


use App\Service\BasketService;

class TestController extends AbstractController
{
    #[Route('/test', name: 'app_test')]
    public function index(): Response
    {
        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }

    #[Route('/login', name: 'app_login')]
    public function login(): Response
    {
        return $this->render('test/login.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }

    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nomU', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'class' => 'input-nom',
                ],
            ])
            ->add('prenomU', TextType::class, [
                'label' => 'Prénom',
                'attr' => [
                    'class' => 'input-prenom',
                ],
            ])
            ->add('emailU', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'class' => 'input-email',
                ],
            ])
            ->add('adresse', TextType::class, [
                'label' => 'Adresse',
                'attr' => [
                    'class' => 'input-adresse',
                ],
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe ne correspondent pas',
                'options' => ['attr' => ['class' => 'input-password']],
                'required' => true,
                'first_options' => ['label' => 'Password'],
                'second_options' => ['label' => 'Confirmez le password'],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'label' => 'J\'accepte les termes et conditions',
                'mapped' => false,
                'required' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }

    
    #[Route('/signup', name: 'app_signup')]
    public function signup(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encrypt password before persisting to database
            $password = $user->getPassword();
            $user->setPassword(password_hash($password, PASSWORD_BCRYPT));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('signup/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/article/{id}', name: 'app_article2')]
    public function show(int $id, ArticlesRepository $rep)
    {
        $article = $rep->get($id);

         if (!$article) {
             throw $this->createNotFoundException('Article not found');
        }

        return $this->render('testingServices.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/articleArtiste/{idArtiste}', name: 'app_articleArtiste')]
    public function showArticlesArtiste(int $idArtiste, ArticlesRepository $rep)
    {
        
        $articles = $rep->findBy(['idartiste' => $idArtiste]);
        return $this->render('testingServices.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/bask', name: 'app_bask')]
    public function index2(): Response
    {
        // Create a new Basket entity
        $basket = new Basket();
        $basket->setIdClient(1);
        $basket->setIdArticle(2);
        $basket->setDateAjout(new \DateTime());

        // Persist the entity to the database
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($basket);
        $entityManager->flush();

        // Return a response to indicate success
        return new Response('Basket entity created successfully');
    }

    #[Route('/bask1', name: 'app_bask1')]
    public function index3(): Response
{
    // Get all Basket entities from the database
    $entityManager = $this->getDoctrine()->getManager();
    $basketRepository = $entityManager->getRepository(Basket::class);
    $baskets = $basketRepository->findAll();

    // Do something with the entities
    // For example, you could output them to the browser
    $response = '';
    foreach ($baskets as $basket) {
        $response .= $basket->getIdClient()->getNomu() . ' - ' . $basket->getIdArticle()->getNoma() . ' - ' . $basket->getDateAjout()->format('Y-m-d H:i:s') . '<br>';
    }

    // Return a response to indicate success
    return new Response($response);
}

#[Route('/bask2', name: 'app_bask2')]
public function viewBasket(BasketRepository $basketRepository)
{
    $basketData = $basketRepository->findAll();

    // dd ($basketData);

    return $this->render('testingServices.html.twig', [
         'basketData' => $basketData,
     ]);
}



#[Route('/bask3', name: 'app_bask3')]
public function viewBasket2( BasketService $basketService)
{
    $basketData = $basketService->getCartItems(32);

    return $this->render('testingServices.html.twig', [
         'basketData' => $basketData,
     ]);
}


}
