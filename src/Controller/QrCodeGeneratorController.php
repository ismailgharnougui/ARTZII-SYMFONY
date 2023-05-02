<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

use Endroid\QrCode\QrCode;


class QrCodeGeneratorController extends AbstractController
{

    public function generate()
{
    $qrCode = new QrCode('Hello, world!');

    $imageData = $qrCode->writeDataUri();

    return new Response('<img src="' . $imageData . '" alt="Hello, world!">');

}
}