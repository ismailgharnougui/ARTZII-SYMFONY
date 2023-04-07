<?php

namespace App\Controller;

use App\Repository\CommandsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\UtilisateurRepository;
use App\Service\BasketService;
usE App\Service\CommandService;
use App\Entity\Commands;
use App\Entity\CommandArticles;
use App\Repository\CommandArticlesRepository;

class CommandsController extends AbstractController
{
    #[Route('/command', name: 'app_commands')]
    public function index(BasketService $basketService, UtilisateurRepository $userRep): Response
    {
        $connectedUser = $userRep->find(32);
        $basketData = $basketService->getCartItems(32);

        $totalPrice = array_reduce($basketData, function ($total, $product) {
            return $total + $product->getIdArticle()->getArtprix();
        }, 0);

        return $this->render('commands/command.html.twig', [
            'controller_name' => 'CommandsController',
            'basketData' => $basketData,
            'totalPrice' => $totalPrice,
            'connectedUser' => $connectedUser
        ]);
    }

    #[Route('/confirmCommand/{livMethod}/{payMethod}', name: 'app_confirmCommand')]
    public function ajoutCommand(
        CommandsRepository $commandsRepository,
        UtilisateurRepository $userRep,
        BasketService $basketService,
        CommandArticlesRepository $commandArticlesRep,
        $livMethod, $payMethod
        ): Response {

        $connectedUser = $userRep->find(32);
        $command = new Commands();
       
        $command->setDateCommande(new \DateTime());
        $command->setIdClient($connectedUser);
        $command->setEtatCommande('En Attente');

        $basketData = $basketService->getCartItems(32);

        $totalPrice = array_reduce($basketData, function ($total, $product) {
            return $total + $product->getIdArticle()->getArtprix();
        }, 0);
        $command->setCoutTotale($totalPrice + 8);

        $command->setAdresse($connectedUser->getAdresse());
        
        $command->setModeLivraison($livMethod);

        $command->setModePaiement($payMethod);

        $commandsRepository->save($command, true);

        foreach ($basketData as $basketItem) {
            $commandArticle = new CommandArticles();
            $commandArticle->setCommand($command);
            $commandArticle->setArticle($basketItem->getIdArticle());
            $commandArticlesRep->save($commandArticle, true);
        }


        $basketService->emptyCart(32);

        // add flash message
        $this->addFlash('success', 'Commande effectuée avec succès');

        return $this->redirectToRoute('app_articles');
    }

    #[Route('/backCommand', name: 'app_backCommand')]
    public function backCommand(CommandsRepository $rep): Response
    {
        $commands = $rep->findAll();
        return $this->render('commands/backCommands.html.twig', [
            'controller_name' => 'CommandsController',
            'commands' => $commands
        ]);
    }

    #[Route('/afficheCommandClient/{idCommand}', name: 'app_afficheCommandClient')]
    public function afficheCommand(CommandsRepository $rep, $idCommand,CommandArticlesRepository $commandArticlesRep , CommandService $commandServ): Response
    {
        $numCommand= $commandServ->generateOrderNumber($idCommand);
        $command = $rep->find($idCommand);

        $commandArticles = $commandArticlesRep->findBy(['command' => $idCommand]);

        return $this->render('commands/affichageCommand.html.twig', [
            'controller_name' => 'CommandsController',
            'command' => $command,
            'numCommand' => $numCommand,
            'commandArticles' => $commandArticles,
        ]);
    }

    #[Route('/commandHistory', name: 'app_commandHistory')]
    public function commandHistory(CommandsRepository $rep): Response
    {
        $Encourslist = [];
        $EnAttentelist=[];
        $Livrelist=[];
        $Annulelist=[];
        // Récupère toutes les commandes du client
        $commands = $rep->findBy(['idClient' => 32]);

        // Parcourt chaque commande
        foreach ($commands as $command) {
            // Vérifie si la commande est en cours
            if ($command->getEtatCommande() == "En cours") {
                // Ajoute la commande à la liste des commandes en cours
                $Encourslist[] = $command;
            }
        }

        foreach ($commands as $command) {
            // Vérifie si la commande est en attente
            if ($command->getEtatCommande() == "En Attente") {
                // Ajoute la commande à la liste des commandes en cours
                $EnAttentelist[] = $command;
            }
        }

        foreach ($commands as $command) {
            // Vérifie si la commande est en cours
            if ($command->getEtatCommande() == "Livré") {
                // Ajoute la commande à la liste des commandes en cours
                $Livrelist[] = $command;
            }
        }

        foreach ($commands as $command) {
            // Vérifie si la commande est en cours
            if ($command->getEtatCommande() == "Annulé") {
                // Ajoute la commande à la liste des commandes en cours
                $Annulelist[] = $command;
            }
        }

        return $this->render('commands/listCommandsClient.html.twig', [
            'controller_name' => 'CommandsController',
            'commands' => $commands,
            'Encourslist' => $Encourslist,
            'EnAttentelist' => $EnAttentelist,
            'Livrelist' => $Livrelist,
            'Annulelist' => $Annulelist
        ]);
    }


    #[Route('/removeCommand/{idCommand}', name: 'app_removeCommand')]
    public function removeArticle($idCommand, CommandsRepository $commandRep)
    {
        $command = $commandRep->find($idCommand);
      
        if (!$command) {
            throw new \Exception('Article not found');
        }

        $commandRep->remove($command, true);

        return $this->redirectToRoute('app_commandHistory');
    }


    #[Route('/updateCommand/{idCommand}/{etatCommand}', name: 'app_updateCommand')]
    public function updateCommand($idCommand, $etatCommand, CommandsRepository $commandRep)
    {
        $command = $commandRep->find($idCommand);
      
        if (!$command) {
            throw new \Exception('Article not found');
        }

        $command->setEtatCommande($etatCommand);

        $commandRep->save($command, true);

        return $this->redirectToRoute('app_commandHistory');
    }

   
}
