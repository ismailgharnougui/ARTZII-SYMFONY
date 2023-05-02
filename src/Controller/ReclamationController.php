<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Entity\Reponse;
use App\Form\ReclamationType;
use App\Form\ReponseType;
use App\Repository\ReclamationRepository;
use App\Repository\ReponseRepository;
use Doctrine\ORM\EntityManagerInterface;

use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\WebLink\Link;

#[Route('/admin/reclamation')]
class ReclamationController extends AbstractController
{
    #[Route('/', name: 'app_reclamation_index', methods: ['GET'])]
    public function index(ReclamationRepository $reclamationRepository): Response
    {
        
        return $this->render('reclamation/index.html.twig', [
            'reclamations' => $reclamationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_reclamation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ReclamationRepository $reclamationRepository): Response
    {
        $reclamation = new Reclamation();
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reclamationRepository->save($reclamation, true);

            return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reclamation/new.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reclamation_show', methods: ['GET'])]
    public function show(Reclamation $reclamation, Request $request, ReponseRepository $reponseRepository): Response
    {
        // create a new response entity and form
        $response = new Reponse();
        $responseForm = $this->createForm(ReponseType::class, $response);

        // handle the submission of the response form
        $responseForm->handleRequest($request);
        if ($responseForm->isSubmitted() && $responseForm->isValid()) {
            // set the reclamation and save the response
            $response->setReclamation($reclamation);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($response);
            $entityManager->flush();

            return $this->redirectToRoute('app_reclamation_show', ['id' => $reclamation->getId()]);
        }

        // render the template with the response form
        return $this->render('reclamation/show.html.twig', [
            'reclamation' => $reclamation,
            'response_form' => $responseForm->createView(),
        ]);
    }


    #[Route('/{id}/edit', name: 'app_reclamation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reclamation $reclamation, ReclamationRepository $reclamationRepository): Response
    {
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reclamationRepository->save($reclamation, true);

            return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
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

    public function frontdelete(Request $request, Reclamation $reclamation, ReclamationRepository $reclamationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reclamation->getId(), $request->request->get('_token'))) {
            $reclamationRepository->remove($reclamation, true);
        }

        return $this->redirectToRoute('front_mesReclamation_show', [], Response::HTTP_SEE_OTHER);
    }

    public function reclamationsbyUser(Request $request,ReclamationRepository $reclamationRepository,PaginatorInterface $paginator): Response
    {
        $recs = $reclamationRepository->getReclamationsByUser($this->getUser());
        $pagination = $paginator->paginate(
            $recs,
            $request->query->getInt('page', 1), // Get the page parameter from the request (default to 1)
            1 // Items per page
        );

        return $this->render('reclamation/mesReclamations.html.twig', [
            'pagination' => $pagination,
        ]);

    }


    public function frontshow(Reclamation $reclamation): Response
    {

        return $this->render('reclamation/frontshow.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }

    public function frontedit(Request $request, Reclamation $reclamation, ReclamationRepository $reclamationRepository): Response
    {
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reclamationRepository->save($reclamation, true);

            return $this->redirectToRoute('front_mesReclamation_show', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reclamation/frontedit.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }
    /**
     * @Route("/reclamation/{id}/add-response", name="app_reclamation_add_response", methods={"POST"})
     */
    public function addResponse(Request $request, Reclamation $reclamation): Response
    {
        $response = new Reponse();
        $response->setReclamation($reclamation);

        $response_form = $this->createForm(ReponseType::class, $response);

        $response_form->handleRequest($request);

        if ($response_form->isSubmitted() && $response_form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($response);
            $entityManager->flush();

            return $this->redirectToRoute('app_reclamation_show', ['id' => $reclamation->getId()]);
        }

        return $this->render('reclamation/add_response.html.twig', [
            'reclamation' => $reclamation,
            'response_form' => $response_form->createView(),
        ]);
    }


    public function push(HubInterface $hub): Response
    {


        return new Response('published!');
    }

    public function discover(Request $request)
    {
       $hubUrl = $this->getParameter('mercure.default_hub');
       $this->addLink($request,new Link('mercure', $hubUrl));
       return $this->json('Done!');
    }


}
