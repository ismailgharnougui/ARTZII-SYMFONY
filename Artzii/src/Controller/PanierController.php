<?php

namespace App\Controller;
use App\Service\BasketService;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\ArticleRepository;
use App\Repository\BasketRepository;
use App\Repository\UtilisateurRepository;

class PanierController extends AbstractController
{
    #[Route('/panier', name: 'app_panier')]
    public function index( BasketService $basketService, UtilisateurRepository $userRep ): Response
    {
        $basketData = $basketService->getCartItems(32);
        $connectedUser = $userRep->find(32);

        $totalPrice = array_reduce($basketData , function ($total, $product) {
            return $total + $product->getIdArticle()->getArtprix();
        }, 0);

        return $this->render('panier/panier.html.twig', [
            'controller_name' => 'PanierController',
            'basketData' => $basketData,
            'totalPrice' => $totalPrice,
            'connectedUser' => $connectedUser
        ]);
    }


    #[Route('/addToBasket/{idArticle}', name: 'app_addToBasket')]
    public function addToBasket($idArticle, BasketService $basketService, UtilisateurRepository $userRep , ArticleRepository $articleRep): Response
    {
        $basketService->addToCart(32, $idArticle, $userRep , $articleRep);
        
        // add flash message
        $this->addFlash('command_ajoute', 'Article ajouté au panier');

        return $this->redirectToRoute('app_articles');
    }

    #[Route('/removeFromBasket/{idArticle}', name: 'app_removeFromBasket')]
    public function removeFromBasket($idArticle, BasketService $basketService, BasketRepository $basketRep): Response
    {
        $basketService->removeFromCart($idArticle, $basketRep);
        return $this->redirectToRoute('app_panier');
    }
    
}
