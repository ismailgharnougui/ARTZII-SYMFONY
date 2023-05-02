<?php
namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Livraison;
use DateTime;
use Symfony\Component\HttpFoundation\Request;

use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeliveryController extends AbstractController
{
    /**
     * @Route("/delivery", name="delivery_index")
     */
    public function index(): Response
    {
        $commandes = $this->getDoctrine()->getRepository(Commande::class)->findAll();

        return $this->render('delivery/delivery_orders.html.twig', [
            'commandes' => $commandes,
        ]);
    }

    /**
     * @Route("/delivery/accept/{id}", name="delivery_accept")
     */
    public function accept($id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $commande = $entityManager->getRepository(Commande::class)->find($id);

        if (!$commande) {
            throw $this->createNotFoundException('No commande found for id '.$id);
        }

        $livraison = new Livraison();
        $livraison->addCommande($commande);
        $livraison->setLivreur($this->getUser());
        $livraison->setClient($commande->getUser());
        $livraison->setDestination($commande->getUser()->getAdresse());
        $date = new DateTime();
        $date->modify('+2 days');
        $livraison->setDateLivraison($date);

        $entityManager->persist($livraison);
        $entityManager->flush();

        return $this->redirectToRoute('mesLivraisons');
    }


    /**
     * @Route("/delivery/orders/{id}", name="delivery_order_detail")
     */
    public function delivery_show_order(Commande $commande): Response
    {
        return $this->render('delivery/delivery_order_detail.html.twig', [
            'commande' => $commande,
        ]);
    }


    /**
     * @Route("/delivery/myDeliveries/", name="mesLivraisons")
     */
    public function listDeliveriesForDeliveryAgent(Request $request,PaginatorInterface $paginator): Response
    {
        $deliveryAgent = $this->getUser();
        $deliveries = $this->getDoctrine()
            ->getRepository(Livraison::class)
            ->findByDeliveryAgent($deliveryAgent);

        $pagination = $paginator->paginate(
            $deliveries,
            $request->query->getInt('page', 1), // Get the page parameter from the request (default to 1)
            1 // Items per page
        );
        return $this->render('delivery/mes_livraisons.html.twig', [
            'pagination' => $pagination,
        ]);
    }
    /**
     * @Route("/delivery/myDeliveries/map", name="mesLivraisonsMap")
     */
    public function listDeliveriesForDeliveryAgentmap(): Response
    {
        $deliveryAgent = $this->getUser();
        $deliveries = $this->getDoctrine()
            ->getRepository(Livraison::class)
            ->findByDeliveryAgent($deliveryAgent);

        return $this->render('delivery/mapView.html.twig', [
            'deliveries' => $deliveries,
        ]);
    }
    /**
     * @Route("/delivery/myDeliveries/map/{id}", name="detailLivraisonMap")
     */
    public function deliveryDetailmap(Livraison $livraison): Response
    {


        return $this->render('delivery/detailmapView.html.twig', [
            'delivery' => $livraison,
        ]);
    }
}
