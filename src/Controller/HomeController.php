<?php
// src/Controller/HomeController.php

namespace App\Controller;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class HomeController extends AbstractController
{

    /**
     * @Route("/")
     */
    public function homepage(): Response
    {
        return $this->render('Artzii/home.html.twig');
    }
   
        #[Route('/admin', name: 'app_admin_index', methods: ['GET'])]
        public function index(UserRepository $userRepository): Response
        {
            return $this->render('Artzii/adminHome.html.twig', [
                'clients' => $userRepository->findAll(),
            ]);
        }    
    public function adminDashboard(): Response
    {
        return $this->render('Artzii/adminHome.html.twig');
    }
    public function LivreurDashboard(): Response
    {
        return $this->render('Artzii/livreurHome.html.twig');
    }

    public function userHome(): Response
    {
        return $this->render('profile/myAccount.html.twig');
    }


}