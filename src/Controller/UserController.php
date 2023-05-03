<?php

namespace App\Controller;
use App\Entity\User;
use App\Form\UserType;
use PhpOffice\PhpSpreadsheet;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


use Symfony\Component\String\Slugger\SluggerInterface;
use Dompdf\Dompdf;

class UserController extends AbstractController
{
    #[Route('/utilisateurs', name: 'display_User')]
    public function index(): Response
    {

        $em = $this->getDoctrine()->getManager()->getRepository(User::class); // ENTITY MANAGER ELY FIH FONCTIONS PREDIFINES

        $User = $em->findAll(); // Select * from user;
       
       
        return $this->render('User/index.html.twig', [
            'controller_name'=> 'UserController',
            'users'=>$User]);

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

    #[Route('/suppressionUser/{id}', name: 'suppressionUser')]
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

 /**
     * @Route("/ajax_search/", name="ajax_search")
     */
    public function chercherUsers(\Symfony\Component\HttpFoundation\Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $requestString = $request->get('q');

        $x = $em
            ->createQuery(
                'SELECT P
            FROM App\Entity\User P
            WHERE P.nom LIKE :str'
            )
            ->setParameter('str', '%' . $requestString . '%')->getResult();


        $products = $x;
        dd($products);
        if (!$products) {
            $result['products']['error'] = "User non trouvé :( ";
        } else {
            $result['products'] = $this->getRealEntities($products);
        }
        return new Response(json_encode($result));
    }
    public function getRealEntities($products)
    {
        foreach ($products as $products) {
            $realEntities[$products->getId()] = [$products->getUsername()];
    
        }
        return $realEntities;
    }

    #[Route('/exportExcel', name: 'exportExcel')]
public function exportExcel()
{
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Add headers to the sheet
    $sheet->setCellValue('A1', 'email');
    $sheet->setCellValue('B1', 'nom');
    $sheet->setCellValue('C1', 'prenom');
    $sheet->setCellValue('D1', 'adresse');
    $sheet->setCellValue('E1', 'phone');

    // Get the products from the database
    $products = $this->getDoctrine()->getRepository(User::class)->findAll();

    // Add the products to the sheet
    $row = 2;
    foreach ($products as $product) {
        $sheet->setCellValue('A' . $row, $product->getEmail());
        $sheet->setCellValue('B' . $row, $product->getNom());
        $sheet->setCellValue('C' . $row, $product->getPrenom());
        $sheet->setCellValue('D' . $row, $product->getAdresse());
        $sheet->setCellValue('E' . $row, $product->getPhone());
        $row++;
    }


    // Create the Excel file
    $writer = new Xlsx($spreadsheet);
    $filename = 'listeUtilisateurs.xlsx';
    $writer->save($filename);

    // Return the Excel file as a response
    return $this->file($filename);
}
}



