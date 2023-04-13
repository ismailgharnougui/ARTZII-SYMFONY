<?php

namespace App\Controller;
use App\Entity\Article;
use App\Form\ArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    #[Route('/produits', name: 'display_prod')]
    public function index(): Response
    {

        $em = $this->getDoctrine()->getManager()->getRepository(Article::class); // ENTITY MANAGER ELY FIH FONCTIONS PREDIFINES

        $prods = $em->findAll(); // Select * from produits;
        return $this->render('article/index.html.twig', ['listP'=>$prods]);
    }



    #[Route('/produits/front', name: 'display_prod_front')]
    public function indexfront(): Response
    {

        $em = $this->getDoctrine()->getManager()->getRepository(Article::class); // ENTITY MANAGER ELY FIH FONCTIONS PREDIFINES

        $prods = $em->findAll(); // Select * from produits;
        return $this->render('article/indexfront.html.twig', ['listP'=>$prods]);
    }

    #[Route('/ajouterArticle', name: 'ajouterArticle')]
    public function ajouterArticle(Request $request): Response
    {

        $prod = new Article(); // init objet
        $form = $this->createForm(ArticleType::class,$prod);




        $form->handleRequest($request); //



        if($form->isSubmitted() && $form->isValid()) {

            $fileUpload= $form->get('ArtImg')->getData();

            $fileName= md5(uniqid()). '.' .$fileUpload->guessExtension();

            $fileUpload->move($this->getParameter('kernel.project_dir').'/public/uploads',$fileName);// Creation dossier uploads

            $prod->setArtImg($fileName);


            $em = $this->getDoctrine()->getManager(); // ENTITY MANAGER ELY FIH FONCTIONS PREDIFINES
            $em->persist($prod);//ajout
            $em->flush();// commit
            /// bch n7adhr hne message elybch naffichia ba3d ajout  w nhot fiha description ta3ha :
            $this->addFlash(
                'notice', 'Article a été bien ajoutée '
            );




            return $this->redirectToRoute('display_prod');

        }

        return $this->render('article/createArticle.html.twig',
            ['f'=>$form->createView()]
        );
    }

    #[Route('/modifierArticle/{id}', name: 'modifierArticle')]
    public function modifierArticle(Request $request,$id): Response
    {
        $prod= $this->getDoctrine()->getManager()->getRepository(Article::class)->find($id);

        $form = $this->createForm(ArticleType::class,$prod);

        $form->handleRequest($request); // bch man5srhomich ya3ni les donnees yab9o persisté



        if($form->isSubmitted() && $form->isValid()) {

            $fileUpload= $form->get('ArtImg')->getData(); // recuperriha fikle (valeur image

            $fileName= md5(uniqid()). '.' .$fileUpload->guessExtension(); //Cryptage image

            $fileUpload->move($this->getParameter('kernel.project_dir').'/public/uploads',$fileName);// Creation dossier uploads

            $prod->setArtImg($fileName);// colonne ta3 image bch nsob fiha esem image crypté

            $em = $this->getDoctrine()->getManager(); // ENTITY MANAGER ELY FIH FONCTIONS PREDIFINES
            $em->persist($prod);//ajout
            $em->flush();// commit
            $this->addFlash(
                'notice', 'Article a été bien modifié '
            );

            return $this->redirectToRoute('display_prod');

        }

        return $this->render('article/modifierArticle.html.twig',
            ['f'=>$form->createView()]
        );
    }

    #[Route('/suppressionArticle/{id}', name: 'suppressionArticle')]
    public function suppressionArticle(Article  $prod): Response
    {
        $em = $this->getDoctrine()->getManager();// ENTITY MANAGER ELY FIH FONCTIONS PREDIFINES
        $em->remove($prod);
        //MISE A JOURS
        $em->flush();
        $this->addFlash(
            'noticedelete', 'Article a été bien supprimer '
        );

        return $this->redirectToRoute('display_prod');
    }

    #[Route('/detailArticle/{id}', name: 'detailArticle')]

    public function detailArticle(\Symfony\Component\HttpFoundation\Request $req, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $prod = $em->getRepository(Article::class)->find($id);


        return $this->render('article/detailArticle.html.twig', array(
            'id' => $prod->getId(),
            'name' => $prod->getArtLib(),
            'prix' => $prod->getArtPrix(),
            'artdispo' =>$prod->getArtDispo(),
            'description' => $prod->getArtDesc(),
            'image'=>$prod->getArtImg(),
            'catLib'=>$prod->getCatLib()->getCatLib()
        ));
    }

    #[Route('/detailArticle/front/{id}', name: 'detailArticlefront')]

    public function detailArticlefront(\Symfony\Component\HttpFoundation\Request $req, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $prod = $em->getRepository(Article::class)->find($id);


        return $this->render('article/detailArticlefront.html.twig', array(
            'id' => $prod->getId(),
            'name' => $prod->getArtLib(),
            'prix' => $prod->getArtPrix(),
            'artdispo' =>$prod->getArtDispo(),
            'description' => $prod->getArtDesc(),
            'image'=>$prod->getArtImg(),
            'catLib'=>$prod->getCatLib()->getCatLib()
        ));
    }


}
