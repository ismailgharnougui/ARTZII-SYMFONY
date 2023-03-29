<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategorieController extends AbstractController
{
    #[Route('/categorie', name: 'app_category')]
    public function affichageCategories(): Response
    {

        $em = $this->getDoctrine()->getManager()->getRepository(Categorie::class); // ENTITY MANAGER ELY FIH FONCTIONS PREDIFINES

        $categories = $em->findAll(); // Select * from Catgories;
        return $this->render('categorie/index.html.twig', ['cat'=>$categories]);
    }

    #[Route('/ajoutCategorie', name: 'ajout_category')]
    public function ajoutCategorie(Request $request): Response
    {

        $category = new Categorie(); // init objet
        $form = $this->createForm(CategorieType::class,$category); // jeblna formulaire CategorieType.

        $form->handleRequest($request); // bch man5srhomich ya3ni les donnees yab9o persisté



        if($form->isSubmitted() && $form->isValid()) {


            $em = $this->getDoctrine()->getManager(); // ENTITY MANAGER ELY FIH FONCTIONS PREDIFINES
            $em->persist($category);//ajout
            $em->flush();// commit

            return $this->redirectToRoute('app_category');

        }

        return $this->render('categorie/ajoutCategorie.html.twig',
            ['f'=>$form->createView()]
        );
    }

    #[Route('/modifierCategorie/{id}', name: 'modifier_category')]
    public function modifierCategory(Request $request,$id): Response
    {
        $prod= $this->getDoctrine()->getManager()->getRepository(Categorie::class)->find($id);


        $form = $this->createForm(CategorieType::class,$prod);


        $form->handleRequest($request); // bch man5srhomich ya3ni les donnees yab9o persisté


        if($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager(); // ENTITY MANAGER ELY FIH FONCTIONS PREDIFINES
            $em->persist($prod);//ajout
            $em->flush();// commit

            return $this->redirectToRoute('app_category');

        }

        return $this->render('categorie/modifierCategorie.html.twig',
            ['f'=>$form->createView()]
        );
    }

    #[Route('/supprimerCategorie/{id}', name: 'supprimerCategory')]
    public function supprimerCategory(Categorie $category): Response
    {

        $em = $this->getDoctrine()->getManager();// ENTITY MANAGER ELY FIH FONCTIONS PREDIFINES
        $em->remove($category);
        //MISE A JOURS
        $em->flush();//commit

        return $this->redirectToRoute('app_category');
    }


}
