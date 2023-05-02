<?php

namespace App\Controller;

use App\Repository\LivreurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(LivreurRepository $rep): Response
    {

        $livreurs = $rep->countByRegion();
        $regionL = [];
        $countL = [];
        foreach ($livreurs as $liv) {
            $regionL[] = $liv['regionL'];
            $countL[] = $liv['countL'];
       }

        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
            'regionL' => json_encode($regionL),
            'countL' => json_encode($countL),
        ]);
    }
}
