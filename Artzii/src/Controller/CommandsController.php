<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommandsController extends AbstractController
{
    #[Route('/command', name: 'app_commands')]
    public function index(): Response
    {
        return $this->render('commands/command.html.twig', [
            'controller_name' => 'CommandsController',
        ]);
    }

    // #[Route('/command/{id}', name: 'app_command')]
    // public function command($id): Response
    // {
    //     return $this->render('commands/command.html.twig', [
    //         'controller_name' => 'CommandsController',
    //     ]);
    // }
}
