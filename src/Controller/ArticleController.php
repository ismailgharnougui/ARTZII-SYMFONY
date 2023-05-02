<?php

namespace App\Controller;
use App\Entity\Article;
use App\Entity\User;

use App\Form\ArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\ArticleRepository;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Label\Margin\Margin;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\QrCode;

use Endroid\QrCode\Writer\PngWriter;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use Symfony\Component\String\Slugger\SluggerInterface;
use Dompdf\Dompdf;




class ArticleController extends AbstractController
{
    #[Route('/articles', name: 'display_articles')]
    public function index(): Response
    {

        $em = $this->getDoctrine()->getManager()->getRepository(Article::class); // ENTITY MANAGER ELY FIH FONCTIONS PREDIFINES

        $prods = $em->findAll(); // Select * from produits;
        return $this->render('article/index.html.twig', ['listS'=>$prods]);
    }


    #[Route('/articles/front', name: 'display_prod_front')]
    public function indexfront(Request $request, PaginatorInterface $paginator): Response
    {
        $em = $this->getDoctrine()->getManager()->getRepository(Article::class);

        $repository = $this->getDoctrine()->getRepository(Article::class)->findAll();


        $pagination = $paginator->paginate(
            $repository,
            $request->query->getInt('page', 1), // Current page number
            3 // Number of items per page
        );

        return $this->render('article/indexfront.html.twig', ['listS' => $pagination]);
    }

    #[Route('/ajouterArticle', name: 'ajouterArticle')]
    public function ajouterArticle(Request $request, SluggerInterface $slugger): Response
    {

        $prod = new Article(); // init objet
        $form = $this->createForm(ArticleType::class, $prod);

        $qrCodes = [];


        $form->handleRequest($request); //


        if ($form->isSubmitted() && $form->isValid()) {

            $fileUpload = $form->get('artimg')->getData();

            $fileName = md5(uniqid()) . '.' . $fileUpload->guessExtension();

            $fileUpload->move($this->getParameter('kernel.project_dir') . '/public/uploads', $fileName);// Creation dossier uploads

            $prod->setArtImg($fileName);

            // USER :
            $User = $this->getDoctrine()->getManager()->getRepository(User::class)->find(
                39
            );

            $em = $this->getDoctrine()->getManager();

            $prod->setIdUser($User);

            //GENEREATE QR CODE

            $url = 'https://www.google.com/search?q=';

            $objDateTime = new \DateTime('NOW');
            $dateString = $objDateTime->format('d-m-Y H:i:s');

            $path = dirname(__DIR__, 2) . '/public/';


            // set qrcode
            $result = Builder::create()
                ->writer(new PngWriter())
                ->writerOptions([])
                ->data('Article Name: ' . $prod->getArtlib() . "\n"
                    . 'Article Price: ' . $prod->getArtprix() . "\n"
                    . 'Article Diponibilite: ' . $prod->getArtdispo() . "\n"
                    . 'Article Description: ' . $prod->getArtdesc() . "\n"
                    . 'Article Categorie: ' . $prod->getCatlib() . "\n"
                )
                ->encoding(new Encoding('UTF-8'))
                ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
                ->size(400)
                ->margin(10)
                ->labelText($dateString)
                ->labelAlignment(new LabelAlignmentCenter())
                ->labelMargin(new Margin(15, 5, 5, 5))
                ->logoPath($path . 'uploads/' . $fileName)
                ->logoResizeToWidth('100')
                ->logoResizeToHeight('100')
                ->backgroundColor(new Color(255, 255, 255))
                ->build();

            //generate name
            $namePng = uniqid('', '') . '.png';

            //Save img png
            $result->saveToFile($path . 'uploads/' . $namePng);

            $result->getDataUri();

            $prod->setQrcode($namePng);


            $em->persist($prod);//ajout
            $em->flush();
            $this->addFlash(
                'notice', 'Article a été bien ajoutée '
            );


            return $this->redirectToRoute('display_articles');

        }

        return $this->render('article/createArticle.html.twig',
            ['f' => $form->createView(), 'qrCodes' => $qrCodes]
        );
    }

    #[Route('/modifierArticle/{artid}', name: 'modifierArticle')]
    public function modifierArticle(Request $request, $artid): Response
    {
        $prod = $this->getDoctrine()->getManager()->getRepository(Article::class)->find($artid);

        $form = $this->createForm(ArticleType::class, $prod);

        $form->handleRequest($request); // bch man5srhomich ya3ni les donnees yab9o persisté


        if ($form->isSubmitted() && $form->isValid()) {


            $fileUpload = $form->get('artimg')->getData(); // recuperriha fikle (valeur image

            $fileName = md5(uniqid()) . '.' . $fileUpload->guessExtension(); //Cryptage image

            $fileUpload->move($this->getParameter('kernel.project_dir') . '/public/uploads', $fileName);// Creation dossier uploads

            $prod->setArtImg($fileName);

            $em = $this->getDoctrine()->getManager();
            $em->persist($prod);
            $em->flush();
            $this->addFlash(
                'notice', 'Article a été bien modifié '
            );

            return $this->redirectToRoute('display_articles');

        }

        return $this->render('article/modifierArticle.html.twig',
            ['f' => $form->createView()]
        );
    }

    #[Route('/suppressionArticle/{artid}', name: 'suppressionArticle')]
    public function suppressionArticle(Article $prod): Response
    {
        $em = $this->getDoctrine()->getManager();// ENTITY MANAGER ELY FIH FONCTIONS PREDIFINES
        $em->remove($prod);
        //MISE A JOURS
        $em->flush();
        $this->addFlash(
            'noticedelete', 'Article a été bien supprimer '
        );

        return $this->redirectToRoute('display_articles');
    }

    #[Route('/detailArticle/{artid}', name: 'detailArticle')]
    public function detailArticle(\Symfony\Component\HttpFoundation\Request $req, $artid)
    {
        $em = $this->getDoctrine()->getManager();
        $prod = $em->getRepository(Article::class)->find($artid);


        return $this->render('article/detailArticle.html.twig', array(
            'id' => $prod->getArtid(),
            'name' => $prod->getArtlib(),
            'prix' => $prod->getArtprix(),
            'artdispo' => $prod->getArtdispo(),
            'description' => $prod->getArtdesc(),
            'image' => $prod->getArtimg(),
            'catlib' => $prod->getCatlib(),
            'User' => $prod->getIdUser()->getNomUser() . ' ' . $prod->getIdUser()->getPrenomUser(),
            'mail' => $prod->getIdUser()->getEmailUser()
        ));
    }

    #[Route('/detailArticle/front/{artid}', name: 'detailArticlefront')]
    public function detailArticlefront(\Symfony\Component\HttpFoundation\Request $req, $artid)
    {
        $em = $this->getDoctrine()->getManager();
        $prod = $em->getRepository(Article::class)->find($artid);


        return $this->render('article/detailArticlefront.html.twig', array(
            'id' => $prod->getArtid(),
            'name' => $prod->getArtlib(),
            'prix' => $prod->getArtprix(),
            'artdispo' => $prod->getArtdispo(),
            'description' => $prod->getArtdesc(),
            'image' => $prod->getArtimg(),
            'catlib' => $prod->getCatlib()

        ));
    }

    #[Route('/exportExcel', name: 'exportExcel')]
    public function exportExcel()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Add headers to the sheet
        $sheet->setCellValue('A1', 'artlib');
        $sheet->setCellValue('B1', 'artprix');
        $sheet->setCellValue('C1', 'artdispo');
        $sheet->setCellValue('D1', 'catlib');
        $sheet->setCellValue('E1', 'artdesc');

        // Get the products from the database
        $products = $this->getDoctrine()->getRepository(Article::class)->findAll();

        // Add the products to the sheet
        $row = 2;
        foreach ($products as $product) {
            $sheet->setCellValue('A' . $row, $product->getArtlib());
            $sheet->setCellValue('B' . $row, $product->getArtprix());
            $sheet->setCellValue('C' . $row, $product->getArtdispo());
            $sheet->setCellValue('D' . $row, $product->getCatlib());
            $sheet->setCellValue('E' . $row, $product->getArtdesc());
            $row++;
        }


        // Create the Excel file
        $writer = new Xlsx($spreadsheet);
        $filename = 'services.xlsx';
        $writer->save($filename);

        // Return the Excel file as a response
        return $this->file($filename);
    }



    /**
     * @Route("/ajax_search/", name="ajax_search")
     */
    public function chercherArticles(\Symfony\Component\HttpFoundation\Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $requestString = $request->get('q');

        $x = $em
            ->createQuery(
                'SELECT P
                FROM App\Entity\Article P
                WHERE P.artlib LIKE :str'
            )
            ->setParameter('str', '%' . $requestString . '%')->getResult();


        $products = $x;
        if (!$products) {
            $result['products']['error'] = "Articles non trouvé :( ";
        } else {
            $result['products'] = $this->getRealEntities($products);
        }
        return new Response(json_encode($result));
    }


// LES  attributs
    public function getRealEntities($products)
    {
        foreach ($products as $products) {
            $realEntities[$products->getArtId()] = [$products->getArtimg(), $products->getArtDispo(), $products->getArtlib(), $products->getArtPrix()];

        }
        return $realEntities;
    }



    #[Route('/top', name: 'top')]
    public function afficherTopfiveService()
    {
        $em = $this->getDoctrine()->getManager();

        $query = $em->createQueryBuilder(); // dql
        $query->select('s.artid, s.note')
            ->from('App\Entity\Article', 's')
            ->orderBy('s.note', 'DESC')
            ->setMaxResults(3);
        $res = $query->getQuery();
        $serviceEvalues = $res->execute();
        $note = 0;
        //count
        $i = 0;

        //tableau
        $j = 0;

        foreach ($serviceEvalues as $se) {
            $note = $note + $se["note"];
            $i++;

            $noteMoy = $note / $i;
            $noteMoy = round($noteMoy);

            $service = $em->getRepository(Article::class)->findOneBy(array('artid' => $se['artid']));
            $serviceTop[$j] = $service;
            $j++;
        }
        return $this->render('front/top.html.twig', array('id' => $se['artid'], 'note' => $se['note'], 'topfive' => $serviceTop));
    }

    #[Route('/noterService/{artid}/{note}', name: 'noterService')]
    public function noterService(Request $request, $artid,$note): Response
    {
        $Services = $this->getDoctrine()->getManager()->getRepository(Article::class)->find($artid);

        $form = $this->createForm(ArticleType::class, $Services);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $fileUpload = $form->get('artimg')->getData();
            $fileName = md5(uniqid()) . '.' . $fileUpload->guessExtension();

            $fileUpload->move($this->getParameter('kernel.project_dir') . '/public/uploads', $fileName);

            $Services->setServImg($fileName);
            $Services->setNote($note);



            $em = $this->getDoctrine()->getManager();
            $em->persist($Services);
            $em->flush();
            $this->addFlash(
                'notice', 'Article a été bien noté '
            );

            return $this->redirectToRoute('display_articles');

        }

        return $this->render('article/modifierArticle.html.twig',
            ['f' => $form->createView()]
        );
    }


    /**
     * @Route("/articles/{id}/note", name="service_note")
     */
    public function addNoteToService(Request $request, Article $service)
    {
        $note = $request->request->get('note');

        if ($note) {
            $service->setNote($note);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Note added successfully!');
        } else {
            $this->addFlash('error', 'Note value is required!');
        }

        return $this->redirectToRoute('display_articles');
    }

    #[Route('/getNoterArticlePage/{artid}', name: 'getNoterArticlePage')]
    public function getNoterServicePage(\Symfony\Component\HttpFoundation\Request $req, $artid)
    {
        $em = $this->getDoctrine()->getManager();
        $Services = $em->getRepository(Article::class)->find($artid);


        return $this->render('article/getNoterArticlePage.html.twig', array(
            'Id' => $Services->getArtid(),
            'name' => $Services->getArtlib(),
            'prix' => $Services->getArtprix(),
            'artdispo' => $Services->getArtdispo(),
            'description' => $Services->getArtdesc(),
            'image' => $Services->getArtimg(),
            'catlib' => $Services->getCatlib(),
            'User' => $Services->getIdUser()->getNomUser() . ' ' . $Services->getIdUser()->getPrenomUser(),
            'mail' => $Services->getIdUser()->getEmailUser()


        ));
    }

    #[Route('/exportpdf', name: 'exportpdf')]
    public function exportToPdf(\App\Repository\ArticleRepository $repository): Response
    {
        // Récupérer les données de réservation depuis votre base de données
        $Services = $repository->findAll();

        // Créer le tableau de données pour le PDF
        $tableData = [];
        foreach ($Services as $Services) {
            $tableData[] = [
                'name' => $Services->getArtlib(),
                'prix' => $Services->getArtprix(),
                'artdispo' => $Services->getArtdispo(),
                'description' => $Services->getArtdesc(),
                'catlib' => $Services->getCatlib(),
                'User' => $Services->getIdUser()->getNomUser() . ' ' . $Services->getIdUser()->getPrenomUser(),
                'mail' => $Services->getIdUser()->getEmailUser()
            ];
        }

        // Créer le PDF avec Dompdf
        $dompdf = new Dompdf();
        $html = $this->renderView('article/export-pdf.html.twig', [
            'tableData' => $tableData,
        ]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        // Envoyer le PDF au navigateur
        $response = new Response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="articles.pdf"',
        ]);
        return $response;
    }

    // stat
    #[Route('/dashboard/stat', name: 'stat', methods: ['POST','GET'])]
    public function VoitureStatistics( \App\Repository\ArticleRepository $repo): Response
    {
        $total = $repo->countByCatLib('personnes') +
            $repo->countByCatLib('classique') +
            $repo->countByCatLib('paysages');


        $BMWCount = $repo->countByCatLib('personnes');
        $MercedesCount = $repo->countByCatLib('classique');
        $AudiCount = $repo->countByCatLib('paysages');


        $BmwPercentage = round(($BMWCount / $total) * 100);
        $MercedesPercentage = round(($MercedesCount / $total) * 100);
        $AudiPercentage = round(($AudiCount / $total) * 100);

        return $this->render('article/stat.html.twig', [
            'BMWPercentage' => $BmwPercentage,
            'MercedesPercentage' => $MercedesPercentage,
            'AudiPercentage' => $AudiPercentage,


        ]);
    }

    #[Route('/article/tricroi', name: 'tri', methods: ['GET','POST'])]
    public function triCroissant( \App\Repository\ArticleRepository $ArticleRepository): Response
    {
        $article = $ArticleRepository->findAllSorted();

        return $this->render('article/index.html.twig', [
            'listS' => $article,
        ]);
    }

    #[Route('/article/tridesc', name: 'trid', methods: ['GET','POST'])]
    public function triDescroissant( \App\Repository\ArticleRepository $ArticleRepository): Response
    {
        $article = $ArticleRepository->findAllSorted1();

        return $this->render('article/index.html.twig', [
            'listS' => $article,
        ]);
    }

    #[Route('/article/search', name: 'search2', methods: ['GET', 'POST'])]
    public function search2(Request $request, \App\Repository\ArticleRepository $repo): Response
    {
        $query = $request->query->get('query');
        $id = $request->query->get('artid');
        $artlib = $request->query->get('artlib');
        $catlib = $request->query->get('catlib');

        $article = $repo->advancedSearch($query, $id, $artlib, $catlib);

        return $this->render('article/index.html.twig', [
            'listS' => $article,
        ]);
    }
}