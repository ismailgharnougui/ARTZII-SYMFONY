<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UtilisateurRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Article;
use App\Form\AjoutArticleType;
use App\Repository\BasketRepository;

class ArticlesController extends AbstractController
{

    
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/articles', name: 'app_articles')]
    public function goToArticles(ArticleRepository $rep, BasketRepository $basketRep): Response
    {
        $existingArticles = [];

        $basketItems=$basketRep->findAll();
        $articles = $rep->findAll();


        // Loop through each basket item
        foreach ($basketItems as $basketItem) {
        $articleId = $basketItem->getIdArticle()->getArtid();

        // Check if the article ID exists in the list of articles
        foreach ($articles as $article) {
            if ($article->getArtid() === $articleId) {
                // Add the existing article to the list of existing articles
                $existingArticles[] = $article->getArtid();
                break;
            }
          }
        }

        
        return $this->render('article/articles.html.twig', [
            'articles' => $articles,
            'existingArticles' => $existingArticles,
        ]);
    }

    #[Route('/articlesArtiste/{idArtiste}', name: 'app_articlesArtiste')]
    public function goToArticlesArtiste($idArtiste, ArticleRepository $articleRep, UtilisateurRepository $userRep, Request $request): Response
    {

        $articles = $articleRep->findBy(['idartiste' => $userRep->find($idArtiste)]);

        //ajout d'un article
         // create a new article
         $article = new Article();

         $user =$userRep->find(39);
 
         // create the form
         $form = $this->createForm(AjoutArticleType::class, $article);
 
         // handle the form submission
         $form->handleRequest($request);
         if ($form->isSubmitted() && $form->isValid()) {
            
             // process the form data
             $article->setIdartiste($userRep->find(39));
             $entityManager = $this->getDoctrine()->getManager();
             $entityManager->persist($article);
             $entityManager->flush();
 
             // redirect to a success page or do something else
             return $this->redirectToRoute('app_articles');
         }

        return $this->render('article/articlesArtiste.html.twig', [
            'articles' => $articles,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/removeArticleArtiste/{idArticle}', name: 'app_removeArticleArtiste')]
    public function removeArticle($idArticle, ArticleRepository $articleRep)
    {
        $article = $articleRep->find($idArticle);
        $artiste = $article->getIdartiste()->getIdu();
        if (!$article) {
            throw new \Exception('Article not found');
        }

        $this->entityManager->remove($article);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_articlesArtiste', ['idArtiste' => $artiste]);
    }

    #[Route('/ajoutArticleArtiste', name: 'app_ajoutArticleArtiste')]
    public function addArticle(Request $request, UtilisateurRepository $userRep, ArticleRepository $articleRep): Response
    {
        // create a new article
        $article = new Article();

        $user =$userRep->find(39);

        // create the form
        $form = $this->createForm(AjoutArticleType::class, $article);

        // handle the form submission
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // process the form data
            $article->setIdartiste($userRep->find(39));
            $articleRep->save($article,true);

            // redirect to a success page or do something else
            return $this->redirectToRoute('app_articles');
        }

        // render the form
        return $this->render('article/ajoutArticle.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
