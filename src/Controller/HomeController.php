<?php
// src/Controller/HomeController.php

namespace App\Controller;

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
    public function index(): Response
    {
        return $this->render('Artzii/email.html.twig');
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