<?php
namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Product;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PanierController extends AbstractController
{
    public function showPanier(): Response
    {
        $panier = $this->get('session')->get('panier', []);

        $items = [];
        $total = 0;

        foreach ($panier as $produit) {
            $items[] = [
                'produit' => $produit,
                'quantite' => $produit['quantite'],
                'sousTotal' => $produit['quantite'] * $produit['prix']
            ];

            $total += $produit['quantite'] * $produit['prix'];
        }

        return $this->render('panier/show.html.twig', [
            'items' => $items,
            'total' => $total
        ]);
    }

    public function confirmerCommande(Request $request,SessionInterface $session, EntityManagerInterface $entityManager, CommandeRepository $commandeRepository): Response
    {

        $panier = $session->get('panier', []);

        $commande = new Commande();
        $commande->setDate(new \DateTime());
        $commande->setUser($this->getUser());
        $total = 0;

        foreach ($panier as $produitId => $produitData) {
            $produit = $entityManager->getRepository(Product::class)->find($produitId);

            if ($produit) {
                $quantite = $produitData['quantite'];
                $commande->addProduit($produit);
                $total += $produit->getPrice() * $quantite;
            }
        }

        $commande->setTotal($total);
        $entityManager->persist($commande);
        $entityManager->flush();


        $session->remove('panier');
        $this->addFlash('success', 'Commande effectuée avec succès.');

        return new RedirectResponse($request->headers->get('referer'));
    }

    public function showfrontPanier(): Response
    {
        $panier = $this->get('session')->get('panier', []);

        $items = [];
        $total = 0;

        foreach ($panier as $produit) {
            $items[] = [
                'produit' => $produit,
                'quantite' => $produit['quantite'],
                'sousTotal' => $produit['quantite'] * $produit['prix']
            ];

            $total += $produit['quantite'] * $produit['prix'];
        }

        return $this->render('panier/front.html.twig', [
            'items' => $items,
            'total' => $total
        ]);
    }
}
