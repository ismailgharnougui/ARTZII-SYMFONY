<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Entity\Utilisateur;
use App\Form\LoginType;


class RegistrationController extends AbstractController
{
    // #[Route('/register', name: 'app_register')]
    // public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    // {
    //     $user = new User();
    //     $form = $this->createForm(RegistrationFormType::class, $user);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         // encode the plain password
    //         $user->setMdpU(
    //             $userPasswordHasher->hashPassword(
    //                 $user,
    //                 $form->get('plainPassword')->getData()
    //             )
    //         );

    //         $entityManager->persist($user);
    //         $entityManager->flush();
    //         // do anything else you need here, like send an email

    //         return $this->redirectToRoute('app_test');
    //     }

    //     return $this->render('registration/register.html.twig', [
    //         'registrationForm' => $form->createView(),
    //     ]);
    // }
    

    #[Route('/login', name: 'app_login')]
    public function login(Request $request)
    {
        // Create the login form
        $form = $this->createForm(LoginType::class);

        // Handle the form submission
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Get the submitted email and password
            $data = $form->getData();
            $email = $data['email'];
            $password = $data['password'];

            // Find the user with the submitted email
            $userRepository = $this->getDoctrine()->getRepository(Utilisateur::class);
            $user = $userRepository->findOneBy(['emailu' => $email]);

            // If the user doesn't exist, or the password is incorrect, show an error message
            if (!$user || !password_verify($password, $user->getMdpu())) {
                $this->addFlash('error', 'Invalid email or password.');

                return $this->redirectToRoute('app_login');
            }

            // If the user exists and the password is correct, log the user in
            $this->addFlash('success', 'You have been logged in.');

            // TODO: Implement the user login logic (e.g. store the user in the session)

            return $this->redirectToRoute('app_articles');
        }

        // Render the login form
        return $this->render('login.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
