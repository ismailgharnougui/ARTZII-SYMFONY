<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Entity\Utilisateur;
use App\Form\ReclamationType;
use App\Repository\ReclamationRepository;
use App\Repository\UtilisateurRepository;
use phpDocumentor\Reflection\DocBlock\Tags\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Validator\ValidatorInterface;


#[Route('/reclamation')]
class ReclamationController extends AbstractController
{
    #[Route('/', name: 'app_reclamation_index', methods: ['GET'])]
    public function index(ReclamationRepository $reclamationRepository,UtilisateurRepository $userRepository): Response
    {
        $user=new Utilisateur();
        $user=$userRepository->find(32);
        return $this->render('reclamation/index.html.twig', [
            'reclamations' => $reclamationRepository->findBy(['id_user'=>$user]),
        ]);
    }
    #[Route('/adminreclamation/statistique', name: 'app_admin_reclamation_statistique', methods: ['GET'])]
    public function statistique(ReclamationRepository $reclamationRepository,UtilisateurRepository $userRepository): Response
    {
        $reclmationresolut=count($reclamationRepository->findBy(['etat'=>"resolu"]));
        $reclmationrenonsolut=count($reclamationRepository->findBy(['etat'=>'nonresolu']));

        return $this->render('reclamation/statistique.html.twig', [
            'reclmationresolut' => $reclmationresolut,
            'reclmationrenonsolut'=>$reclmationrenonsolut

        ]);
    }
    #[Route('/adminreclamation', name: 'app_admin_reclamation', methods: ['GET'])]
    public function adminreclamation(ReclamationRepository $reclamationRepository,UtilisateurRepository $userRepository): Response
    {

        return $this->render('reclamation/admin_index.html.twig', [
            'reclamations' => $reclamationRepository->findAll(),

        ]);
    }

    #[Route('/adminreclamation/{id}', name: 'app_reclamation_show_admin', methods: ['GET'])]
    public function showadmin(Reclamation $reclamation): Response
    {
        return $this->render('reclamation/admin_show.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }



    #[Route('/new', name: 'app_reclamation_new', methods: ['GET', 'POST'])]
    public function new(ValidatorInterface $validator,Request $request, ReclamationRepository $reclamationRepository,UtilisateurRepository $userRepository): Response
    {   $user=new Utilisateur();
        $user=$userRepository->find(32);


        $reclamation = new Reclamation();
        $reclamation->setEtat("nonresolu");
        $reclamation->setIdUser($user);

        $reclamation->setDateR(new \DateTime());
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);


        if ($form->isSubmitted()) {
            $errors = $validator->validate($reclamation);
            if (count($errors) === 0) {
                $reclamationRepository->save($reclamation, true);

                return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
            }
            else {
                // The data is not valid, add the errors to the form
                foreach ($errors as $error) {
                    $form->addError(new FormError($error->getMessage()));
                }

            }

        }

        return $this->renderForm('reclamation/new.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reclamation_show', methods: ['GET'])]
    public function show(Reclamation $reclamation): Response
    {
        return $this->render('reclamation/show.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reclamation_edit', methods: ['GET', 'POST'])]
    public function edit(ValidatorInterface $validator,Request $request, Reclamation $reclamation, ReclamationRepository $reclamationRepository): Response
    {
        $form = $this->createForm(ReclamationType::class, $reclamation);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $errors = $validator->validate($reclamation);
            if (count($errors) === 0) {
                $reclamationRepository->save($reclamation, true);
                return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);

            }
            else {
                // The data is not valid, add the errors to the form
                foreach ($errors as $error) {
                    $form->addError(new FormError($error->getMessage()));
                }

            }

        }



        return $this->renderForm('reclamation/edit.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reclamation_delete', methods: ['POST'])]
    public function delete(Request $request, Reclamation $reclamation, ReclamationRepository $reclamationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reclamation->getId(), $request->request->get('_token'))) {
            $reclamationRepository->remove($reclamation, true);
        }

        return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('deleteparadmin/{id}', name: 'app_reclamation_delete_paradmin', methods: ['POST'])]
    public function delete_paradmin(Request $request, Reclamation $reclamation, ReclamationRepository $reclamationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reclamation->getId(), $request->request->get('_token'))) {
            $reclamationRepository->remove($reclamation, true);
        }

        return $this->redirectToRoute('app_admin_reclamation', [], Response::HTTP_SEE_OTHER);
    }
}
