<?php

namespace App\Controller;
use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/User', name: 'display_User')]
    public function index(): Response
    {

        $em = $this->getDoctrine()->getManager()->getRepository(User::class); // ENTITY MANAGER ELY FIH FONCTIONS PREDIFINES

        $User = $em->findAll(); // Select * from user;
        return $this->render('User/index.html.twig', ['listP'=>$User]);
    }

    #[Route('/ajouterUser', name: 'ajouterUser')]
    public function ajouterUser(Request $request): Response
    {

        $User = new User(); // init objet
        $form = $this->createForm(UserType::class,$User);




        $form->handleRequest($request); // bch man5srhomich ya3ni les donnees yab9o persisté



        if($form->isSubmitted() && $form->isValid()) {




            $em = $this->getDoctrine()->getManager(); // ENTITY MANAGER ELY FIH FONCTIONS PREDIFINES
            $em->persist($User);//ajout
            $em->flush();// commit
            /// bch n7adhr hne message elybch naffichia ba3d ajout  w nhot fiha description ta3ha :
            $this->addFlash(
                'notice', 'User a ete ajoutée '
            );




            return $this->redirectToRoute('display_User');

        }

        return $this->render('User/createUser.html.twig',
            ['f'=>$form->createView()]
        );
    }

    #[Route('/modifierUser/{idU}', name: 'modifierUser')]
    public function modifierUser(Request $request,$idU): Response
    {
        $User= $this->getDoctrine()->getManager()->getRepository(User::class)->find($idU);

        $form = $this->createForm(UserType::class,$User);

        $form->handleRequest($request); // bch man5srhomich ya3ni les donnees yab9o persisté



        if($form->isSubmitted() && $form->isValid()) {









            $em = $this->getDoctrine()->getManager(); // ENTITY MANAGER ELY FIH FONCTIONS PREDIFINES
            $em->persist($User);//ajout
            $em->flush();// commit
            $this->addFlash(
                'notice', 'User a ete bien modifié '
            );

            return $this->redirectToRoute('display_User');

        }

        return $this->render('User/modifierUser.html.twig',
            ['f'=>$form->createView()]
        );
    }

    #[Route('/suppressionUser/{idU}', name: 'suppressionUser')]
    public function suppressionUser(User  $User): Response
    {
        $em = $this->getDoctrine()->getManager();// ENTITY MANAGER ELY FIH FONCTIONS PREDIFINES
        $em->remove($User);
        //MISE A JOURS
        $em->flush();
        $this->addFlash(
            'noticedelete', 'User a ete bien supprimer '
        );

        return $this->redirectToRoute('display_User');
    }






}
