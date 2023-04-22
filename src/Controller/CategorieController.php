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

        $em = $this->getDoctrine()->getManager()->getRepository(Categorie::class);

        $categories = $em->findAll();
        return $this->render('categorie/index.html.twig', ['cat' => $categories]);
    }

    #[Route('/ajoutCategorie', name: 'ajout_category')]
    public function ajoutCategorie(Request $request): Response
    {

        $category = new Categorie();
        $form = $this->createForm(CategorieType::class, $category);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {


            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
            $this->addFlash(
                'notice', 'Catégorie a été bien ajoutée !'
            );
            return $this->redirectToRoute('app_category');

        }

        return $this->render('categorie/ajoutCategorie.html.twig',
            ['f' => $form->createView()]
        );
    }

    #[Route('/modifierCategorie/{catid}', name: 'modifier_category')]
    public function modifierCategory(Request $request, $catid): Response
    {
        $prod = $this->getDoctrine()->getManager()->getRepository(Categorie::class)->find($catid);


        $form = $this->createForm(CategorieType::class, $prod);


        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($prod);//ajout
            $em->flush();// commit
            $this->addFlash(
                'notice', 'Catégorie a été bien modifiée ! '
            );
            return $this->redirectToRoute('app_category');

        }

        return $this->render('categorie/modifierCategorie.html.twig',
            ['f' => $form->createView()]
        );
    }

    #[Route('/supprimerCategorie/{catid}', name: 'supprimerCategory')]
    public function supprimerCategory(Categorie $category): Response
    {

        $em = $this->getDoctrine()->getManager();
        $em->remove($category);
        //MISE A JOUR
        $em->flush();//commit
        $this->addFlash(
            'noticedelete', 'Catégorie a été bien supprimé '
        );
        return $this->redirectToRoute('app_category');
    }

}