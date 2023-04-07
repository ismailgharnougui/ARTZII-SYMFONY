<?php
namespace App\Service;

class CommandService
{
    public function generateOrderNumber($id)
    {
        $orderId = 'ORD' . date('Ymd') . '-' . str_pad($id, 3, '0', STR_PAD_LEFT);
        return $orderId;
    }
}

