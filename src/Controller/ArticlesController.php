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
use App\Service\BasketService;
use Symfony\Component\HttpFoundation\JsonResponse;

class ArticlesController extends AbstractController
{

    
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/articles', name: 'app_articles')]
    public function goToArticles(ArticleRepository $rep, BasketRepository $basketRep, UtilisateurRepository $userRep): Response
    {
        $existingArticles = [];
        $connectedUser = $userRep->find(32);

        $basketItems=$basketRep->findBy(['idClient' => $connectedUser]);
        $basketItemsCount = count($basketItems);

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

        // Randomly shuffle the articles array
        // usort($articles, function ($a, $b) {
        //     return rand(-1, 1);
        // });
        
        return $this->render('article/articles.html.twig', [
            'articles' => $articles,
            'existingArticles' => $existingArticles,
            'basketItemsCount' => $basketItemsCount,
        ]);
    }

    #[Route('/articlesArtiste/{idArtiste}', name: 'app_articlesArtiste')]
    public function goToArticlesArtiste($idArtiste, ArticleRepository $articleRep, UtilisateurRepository $userRep, BasketService $basketService, Request $request): Response
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
         $basketItemsCount= count($basketService->getCartItems(32));


        return $this->render('article/articlesArtiste.html.twig', [
            'articles' => $articles,
            'form' => $form->createView(),
            'basketItemsCount' => $basketItemsCount,
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


    #[Route('apiTest/{question}', name: 'apiTest')]
    public function testApi($question): Response
    {

        $curl = curl_init();
        
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://openai80.p.rapidapi.com/chat/completions",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                        [
                                        'role' => 'user',
                                        'content' => $question
                        ]
                ]
            ]),
            CURLOPT_HTTPHEADER => [
                "X-RapidAPI-Host: openai80.p.rapidapi.com",
                "X-RapidAPI-Key: a18244075dmsh0328f969f916869p162e92jsn6fc0574737fa",
                "content-type: application/json"
            ],
        ]);
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);
        
        if ($err) {
            //echo "cURL Error #:" . $err;
        } else {
            //echo $response;
        
        $response = new JsonResponse($response, Response::HTTP_OK);
        $content = $response->getContent();
        $decodedData = json_decode($content, true);
    
        $response = new Response();
        $response->setContent($decodedData);
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type');

        $response->setStatusCode(Response::HTTP_OK);
        }
        return $response;
    }
    }


