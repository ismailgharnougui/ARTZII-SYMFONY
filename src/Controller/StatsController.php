<?php


namespace App\Controller;

use App\Entity\Product;
use App\Entity\Service;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;
class StatsController extends AbstractController
{

    public function serviceDemandesPieChart(ProductRepository $productRepository)
    {
        $services = $this->getDoctrine()->getRepository(Service::class)->findAll();

        $data = [];
        foreach ($services as $service) {
            $data[] = [
                'category' => $service->getTitre(),
                'value' => $service->getDemandes()->count(),
            ];
        }
        $productData = $productRepository->createQueryBuilder('p')
            ->select('p.categorie AS category, COUNT(p.id) AS value')
            ->groupBy('p.categorie')
            ->getQuery()
            ->getResult();

// initialize the data array
        $product_data = [];

// loop through the product data and add each category and count to the data array
        foreach ($productData as $product) {
            $product_data[] = [
                'category' => $product['category'],
                'value' => $product['value']
            ];
        }

        return $this->render('profile/dashboard.html.twig', [
            'service_data' => $data,
            'product_data' =>$product_data
        ]);

    }



}