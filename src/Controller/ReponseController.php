<?php

namespace App\Controller;

use App\Entity\Reponse;
use App\Form\ReponseType;
use App\Repository\ReclamationRepository;
use App\Repository\ReponseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Twilio\Rest\Client;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;


#[Route('/reponse')]
class ReponseController extends AbstractController
{
    #[Route('/', name: 'app_reponse_index', methods: ['GET'])]
    public function index(ReponseRepository $reponseRepository): Response
    {
        return $this->render('reponse/index.html.twig', [
            'reponses' => $reponseRepository->findAll(),
        ]);
    }

    #[Route('/new/{idreclmaation}', name: 'app_reponse_new', methods: ['GET', 'POST'])]
    public function new(Client $twilio,MailerInterface $mailer,ValidatorInterface $validator,Request $request, ReponseRepository $reponseRepository , ReclamationRepository $reclamationRepository ,$idreclmaation): Response
    {   $reclamation = $reclamationRepository->find($idreclmaation);
        $useremail=$reclamation->getUser()->getEmail();
        $reponse = new Reponse();
        $reponse->setIdreclamation($reclamation);
        $reponse->setDateRep(new \DateTime());

        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $errors = $validator->validate($reponse);
            if (count($errors) === 0) {
                $reponseRepository->save($reponse, true);
                $reclamation->setEtat("resolu");

                $reclamationRepository->save($reclamation,true);
                $message = $twilio->messages->create(
                    '+21628254934',
                    array(
                        'from' => '+12766246381',
                        'body' => 'Votre réclamation a été traitée.'
                    )
                );
                $email=(new Email())
                    ->from('mahdi.mokrani1@esprit.tn')
                    ->to($useremail
                    )
                    ->subject('Votre réclamation a été traitée.')
                    ->text('Reponse : '.$reponse->getContenuRep());
                try {
                    $mailer->send($email);
                } catch (TransportExceptionInterface $e) {
                    dd($e->getMessage());
                    // some error prevented the email sending; display an
                    // error message or try to resend the message
                }


                return $this->redirectToRoute('app_reponse_index', [], Response::HTTP_SEE_OTHER);
            }
            else {
                // The data is not valid, add the errors to the form
                foreach ($errors as $error) {
                    $form->addError(new FormError($error->getMessage()));
                }

            }

        }

        return $this->renderForm('reponse/new.html.twig', [
            'reclamation'=>$reclamation,
            'reponse' => $reponse,
            'form' => $form,
        ]);
    }

    #[Route('/{idRep}', name: 'app_reponse_show', methods: ['GET'])]
    public function show(Reponse $reponse): Response
    {
        return $this->render('reponse/show.html.twig', [
            'reponse' => $reponse,
        ]);
    }

    #[Route('/{idRep}/edit', name: 'app_reponse_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reponse $reponse, ReponseRepository $reponseRepository): Response
    {
        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reponseRepository->save($reponse, true);

            return $this->redirectToRoute('app_reponse_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reponse/edit.html.twig', [
            'reponse' => $reponse,
            'form' => $form,
        ]);
    }

    #[Route('/{idRep}', name: 'app_reponse_delete', methods: ['POST'])]
    public function delete(Request $request, Reponse $reponse, ReponseRepository $reponseRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reponse->getIdRep(), $request->request->get('_token'))) {
            $reponseRepository->remove($reponse, true);
        }

        return $this->redirectToRoute('app_reponse_index', [], Response::HTTP_SEE_OTHER);
    }
}
