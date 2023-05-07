<?php
namespace App\Service;

use App\Entity\Commands;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Component\HttpFoundation\JsonResponse;

class CommandService
{
    public function generateOrderNumber($id)
    {
        $orderId = 'ORD' . date('Ymd') . '-' . str_pad($id, 3, '0', STR_PAD_LEFT);
        return $orderId;
    }

    // public function chartData(EntityManagerInterface $entityManager)
    // {
    //     $data = $entityManager->createQuery(
    //         "SELECT MONTH(c.date_commande) as month, SUM(c.cout_totale) as total 
    //          FROM App\Entity\Commands c 
    //          GROUP BY month"
    //     )->getResult();
    
    //     dd($data);
    // }
}

