<?php

namespace App\Controller;

use App\Entity\Livreur;
use App\Form\LivreurType;
use App\Repository\LivreurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

#[Route('/livreur')]
class LivreurController extends AbstractController
{
    private $flashMessage;
    public function __construct(
        
        FlashBagInterface $flashMessage,
    
    ) {
        
        $this->flashMessage = $flashMessage;
    }
    #[Route('/', name: 'app_livreur_index', methods: ['GET'])]
    public function index(Request $request,LivreurRepository $livreurRepository,PaginatorInterface $paginator): Response
    {
        $livreur =$livreurRepository->findAll();
        $livreur = $paginator->paginate(
            $livreur, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            2/*limit per page*/    
        );
        return $this->render('livreur/index.html.twig', [
            'livreurs' => $livreur,
        ]);
    }

    #[Route('/new', name: 'app_livreur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, LivreurRepository $livreurRepository): Response
    {
        $livreur = new Livreur();
        $form = $this->createForm(LivreurType::class, $livreur);
        $form->add('ajouter',SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $livreurRepository->save($livreur, true);
            $this->flashMessage->add("success", "livreur ajoutée !");

            return $this->redirectToRoute('app_livreur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('livreur/new.html.twig', [
            'livreur' => $livreur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_livreur_show', methods: ['GET'])]
    public function show(Livreur $livreur): Response
    {
        return $this->render('livreur/show.html.twig', [
            'livreur' => $livreur,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_livreur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Livreur $livreur, LivreurRepository $livreurRepository): Response
    {
        $form = $this->createForm(LivreurType::class, $livreur);
        $form->add('edit', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $livreurRepository->save($livreur, true);
            $this->flashMessage->add("success", "livreur addapté   !");


            return $this->redirectToRoute('app_livreur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('livreur/edit.html.twig', [
            'livreur' => $livreur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_livreur_delete', methods: ['POST'])]
    public function delete(Request $request, Livreur $livreur, LivreurRepository $livreurRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$livreur->getId(), $request->request->get('_token'))) {
            $livreurRepository->remove($livreur, true);
            $this->flashMessage->add("success", "livreur supprimé  !");

        }

        return $this->redirectToRoute('app_livreur_index', [], Response::HTTP_SEE_OTHER);
    }
}
