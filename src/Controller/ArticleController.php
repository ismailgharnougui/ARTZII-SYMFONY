<?php

namespace App\Controller;
use App\Entity\Article;
use App\Entity\User;

use App\Form\ArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


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


class ArticleController extends AbstractController
{
    #[Route('/articles', name: 'display_articles')]
    public function index(Request  $request,PaginatorInterface  $paginator): Response
    {

        $em = $this->getDoctrine()->getManager()->getRepository(Article::class);

        $repository = $this->getDoctrine()->getRepository(Article::class)->findAll();



        $pagination = $paginator->paginate(
            $repository,
            $request->query->getInt('page', 1), // Current page number
            3 // Number of items per page
        );

        return $this->render('article/index.html.twig', ['listS'=>$pagination]);
    }



    #[Route('/articles/front', name: 'display_prod_front')]
    public function indexfront(): Response
    {

        $em = $this->getDoctrine()->getManager()->getRepository(Article::class); // ENTITY MANAGER ELY FIH FONCTIONS PREDIFINES

        $prods = $em->findAll(); // Select * from produits;
        return $this->render('article/indexfront.html.twig', ['listS'=>$prods]);
    }

    #[Route('/ajouterArticle', name: 'ajouterArticle')]
    public function ajouterArticle(Request $request,SluggerInterface $slugger): Response
    {

        $prod = new Article(); // init objet
        $form = $this->createForm(ArticleType::class,$prod);

        $qrCodes = [];



        $form->handleRequest($request); //



        if($form->isSubmitted() && $form->isValid()) {

            $fileUpload= $form->get('artimg')->getData();

            $fileName= md5(uniqid()). '.' .$fileUpload->guessExtension();

            $fileUpload->move($this->getParameter('kernel.project_dir').'/public/uploads',$fileName);// Creation dossier uploads

            $prod->setArtImg($fileName);

            // USER :
            $User= $this->getDoctrine()->getManager()->getRepository(User::class)->find(
                39
            );

            $em = $this->getDoctrine()->getManager(); // ENTITY MANAGER ELY FIH FONCTIONS PREDIFINES

            $prod->setIdUser($User);

            //GENEREATE QR CODE

            $url = 'https://www.google.com/search?q=';

            $objDateTime = new \DateTime('NOW');
            $dateString = $objDateTime->format('d-m-Y H:i:s');

            $path = dirname(__DIR__, 2).'/public/';


            // set qrcode
            $result =Builder::create()
                ->writer(new PngWriter())
                ->writerOptions([])
                ->data('Custom QR code contents')
                ->encoding(new Encoding('UTF-8'))
                ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
                ->size(400)
                ->margin(10)
                ->labelText($dateString)
                ->labelAlignment(new LabelAlignmentCenter())
                ->labelMargin(new Margin(15, 5, 5, 5))
                ->logoPath($path.'uploads/'.$fileName)
                ->logoResizeToWidth('100')
                ->logoResizeToHeight('100')
                ->backgroundColor(new Color(255, 255, 255))
                ->build()
            ;

            //generate name
            $namePng = uniqid('', '') . '.png';

            //Save img png
            $result->saveToFile($path.'uploads/'.$namePng);

            $result->getDataUri();

            $prod->setQrcode($namePng);




            $em->persist($prod);//ajout
            $em->flush();// commit
            /// bch n7adhr hne message elybch naffichia ba3d ajout  w nhot fiha description ta3ha :
            $this->addFlash(
                'notice', 'Article a été bien ajoutée '
            );




            return $this->redirectToRoute('display_articles');

        }

        return $this->render('article/createArticle.html.twig',
            ['f'=>$form->createView(),'qrCodes'=>$qrCodes]
        );
    }

    #[Route('/modifierArticle/{artid}', name: 'modifierArticle')]
    public function modifierArticle(Request $request,$artid): Response
    {
        $prod= $this->getDoctrine()->getManager()->getRepository(Article::class)->find($artid);

        $form = $this->createForm(ArticleType::class,$prod);

        $form->handleRequest($request); // bch man5srhomich ya3ni les donnees yab9o persisté



        if($form->isSubmitted() && $form->isValid()) {

            $fileUpload= $form->get('artimg')->getData(); // recuperriha fikle (valeur image

            $fileName= md5(uniqid()). '.' .$fileUpload->guessExtension(); //Cryptage image

            $fileUpload->move($this->getParameter('kernel.project_dir').'/public/uploads',$fileName);// Creation dossier uploads

            $prod->setArtImg($fileName);// colonne ta3 image bch nsob fiha esem image crypté

            $em = $this->getDoctrine()->getManager(); // ENTITY MANAGER ELY FIH FONCTIONS PREDIFINES
            $em->persist($prod);//ajout
            $em->flush();// commit
            $this->addFlash(
                'notice', 'Article a été bien modifié '
            );

            return $this->redirectToRoute('display_articles');

        }

        return $this->render('article/modifierArticle.html.twig',
            ['f'=>$form->createView()]
        );
    }

    #[Route('/suppressionArticle/{artid}', name: 'suppressionArticle')]
    public function suppressionArticle(Article  $prod): Response
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
            'artdispo' =>$prod->getArtdispo(),
            'description' => $prod->getArtdesc(),
            'image'=>$prod->getArtimg(),
            'catlib'=>$prod->getCatlib()
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
            'artdispo' =>$prod->getArtdispo(),
            'description' => $prod->getArtdesc(),
            'image'=>$prod->getArtimg(),
            'catlib'=>$prod->getCatlib()
        ));
    }

    #[Route('/exportExcel', name: 'exportExcel')]

    public function exportExcel() {
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

}