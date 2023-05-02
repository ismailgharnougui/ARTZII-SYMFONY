<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\LivraisonRepository;
use Knp\Component\Pager\PaginatorInterface;



class FrontController extends AbstractController
{
    #[Route('/front', name: 'app_front')]
    public function index(): Response
    {
        return $this->render('front/index.html.twig', [
            'controller_name' => 'FrontController',
        ]);
    }
    #[Route('/front2', name: 'front_livraison2_index', methods: ['GET'])]
    public function index2(Request $request,LivraisonRepository $livraisonRepository,PaginatorInterface $paginator): Response
    {
        $livraisons=$livraisonRepository->findAll();
        $livraisons = $paginator->paginate(
        $livraisons, /* query NOT result */
        $request->query->getInt('page', 1), /*page number*/
        1 /*limit per page*/    
    );
        return $this->render('front/front.html.twig', [
            'livraisons' => $livraisons,
        ]);
    }


}
