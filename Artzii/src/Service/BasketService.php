<?php
// src/Service/CartService.php

namespace App\Service;

use App\Repository\ArticlesRepository;
use App\Repository\UtilisateurRepository;
use App\Repository\BasketRepository;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Basket;

class BasketService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

     public function addToCart($userId, $articleId, UtilisateurRepository $userRep , ArticlesRepository $articleRep)
     {
        $user = $userRep->find($userId);
        $article = $articleRep->find($articleId);

        $panier = new Basket();
        $panier->setIdClient($user);
        $panier->setIdArticle($article);
        $panier->setDateAjout(new \DateTime());

        $this->entityManager->persist($panier);
        $this->entityManager->flush();
      }

    public function getCartItems($userId)
    {
        $panier = $this->entityManager->getRepository(Basket::class)->findBy([
            'idClient' => $userId
        ]);

        return $panier;
    }
    
    public function removeFromCart($basketId, BasketRepository $basketRep)
{
    $basket = $basketRep->find($basketId);

    if (!$basket) {
        throw new \Exception('Basket item not found');
    }

    $this->entityManager->remove($basket);
    $this->entityManager->flush();
}

}
