<?php

namespace App\Controller;

use App\Repository\CoursRepository;
use App\Service\BasketService;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CoursController extends AbstractController
{
    #[Route('/cours', name: 'app_cours')]
    public function index(BasketService $basketService, CoursRepository $coursRep): Response
    {
        $basketItemsCount= count($basketService->getCartItems(32));
        $cours = $coursRep->findAll();


        return $this->render('cours/cours.html.twig', [
            'controller_name' => 'CoursController',
            'basketItemsCount' => $basketItemsCount,
            'cours' => $cours
        ]);
    }

    #[Route('/cours/{idCours}', name: 'app_infoCours')]
    public function showCours ($idCours, BasketService $basketService, CoursRepository $coursRep) : Response
    {
        $basketItemsCount= count($basketService->getCartItems(32));
        $cours = $coursRep->find($idCours);


        return $this->render('cours/infoCours.html.twig', [
            'controller_name' => 'CoursController',
            'basketItemsCount' => $basketItemsCount,
            'cours' => $cours
        ]);

    }

}
