<?php

namespace App\Controller;

use App\Entity\Livreurs;
use App\Form\LivreursType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/livreurs')]
class LivreursController extends AbstractController
{
    #[Route('/', name: 'app_livreurs_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $livreurs = $entityManager
            ->getRepository(Livreurs::class)
            ->findAll();

        return $this->render('livreurs/index.html.twig', [
            'livreurs' => $livreurs,
        ]);
    }

    #[Route('/new', name: 'app_livreurs_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $livreur = new Livreurs();
        $form = $this->createForm(LivreursType::class, $livreur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($livreur);
            $entityManager->flush();

            return $this->redirectToRoute('app_livreurs_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('livreurs/new.html.twig', [
            'livreur' => $livreur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_livreurs_show', methods: ['GET'])]
    public function show(Livreurs $livreur): Response
    {
        return $this->render('livreurs/show.html.twig', [
            'livreur' => $livreur,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_livreurs_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Livreurs $livreur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LivreursType::class, $livreur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_livreurs_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('livreurs/edit.html.twig', [
            'livreur' => $livreur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_livreurs_delete', methods: ['POST'])]
    public function delete(Request $request, Livreurs $livreur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$livreur->getId(), $request->request->get('_token'))) {
            $entityManager->remove($livreur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_livreurs_index', [], Response::HTTP_SEE_OTHER);
    }
}
