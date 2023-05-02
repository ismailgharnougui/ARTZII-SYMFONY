<?php

namespace App\Controller;

use App\Entity\Livraison;
use App\Form\LivraisonType;
use App\Repository\CommandeRepository;
use App\Repository\LivraisonRepository;
use App\Repository\LivreurRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;


use Endroid\QrCode\Builder\BuilderInterface;


#[Route('/livraison')]

class LivraisonController extends AbstractController
{
    private $flashMessage;
    public function __construct(
        
        FlashBagInterface $flashMessage,
    
    ) {
        
        $this->flashMessage = $flashMessage;
    }

    #[Route('/', name: 'app_livraison_index', methods: ['GET'])]
    public function index(Request $request,LivraisonRepository $livraisonRepository,PaginatorInterface $paginator): Response
    {
        $livraisons=$livraisonRepository->findAll();
        $livraisons = $paginator->paginate(
        $livraisons, /* query NOT result */
        $request->query->getInt('page', 1), /*page number*/
        1 /*limit per page*/    
    );
        return $this->render('livraison/index.html.twig', [
            'livraisons' => $livraisons,
        ]);
    }

    #[Route('/new', name: 'app_livraison_new', methods: ['GET', 'POST'])]
    public function new(Request $request, LivraisonRepository $livraisonRepository,CommandeRepository $commandeRepository,LivreurRepository $livreurRepository): Response
    {
        $livraison = new Livraison();
        $form = $this->createForm(LivraisonType::class, $livraison);
        $form->add('ajouter', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $livreur= $livreurRepository->find($livraison->getLivreur()->getId());
            $livreur->setEtat('Non Disponible');
            $commande=$commandeRepository->find($livraison->getCommande()->getId());
            $commande->setEtat(true);
            $commandeRepository->save($commande, true);
            $livraisonRepository->save($livraison, true);
            $this->flashMessage->add("success", "livraison ajoutée !");


            return $this->redirectToRoute('app_livraison_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('livraison/new.html.twig', [
            'livraison' => $livraison,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_livraison_show', methods: ['GET'])]
    public function show(Livraison $livraison): Response
    {
        return $this->render('livraison/show.html.twig', [
            'livraison' => $livraison,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_livraison_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Livraison $livraison, LivraisonRepository $livraisonRepository): Response
    {
        $form = $this->createForm(LivraisonType::class, $livraison);
        $form->add('edit',SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $livraisonRepository->save($livraison, true);
            $this->flashMessage->add("success", "livraison adapté !");


            return $this->redirectToRoute('app_livraison_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('livraison/edit.html.twig', [
            'livraison' => $livraison,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_livraison_delete', methods: ['POST'])]
    public function delete(Request $request, Livraison $livraison, LivraisonRepository $livraisonRepository,LivreurRepository $livreurRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$livraison->getId(), $request->request->get('_token'))) {
            $livreur = $livreurRepository->find($livraison->getLivreur()->getId());
            $livreur->setEtat('Disponible');
            $livraisonRepository->remove($livraison, true);
            $this->flashMessage->add("success", "livraison supprimée !");

        }

        return $this->redirectToRoute('app_livraison_index', [], Response::HTTP_SEE_OTHER);
    }

    
}
