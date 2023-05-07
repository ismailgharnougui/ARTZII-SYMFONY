<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BackControllertwo extends AbstractController
{
    #[Route('/back', name: 'app_back')]
    public function index(): Response
    {
        return $this->render('back/index.html.twig', [
            'controller_name' => 'BackController',
        ]);
    }

    #[Route('/back/dashboard', name: 'app_back_dashboard')]
    public function dashboard(): Response
    {
        return $this->render('back/dashboard.html.twig', [
            'controller_name' => 'BackController',
        ]);
    }
}
