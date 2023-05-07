<?php

namespace App\Controller;

use App\Repository\CommandsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\UtilisateurRepository;
use App\Service\BasketService;
use App\Service\CommandService;
use App\Entity\Commands;
use App\Entity\CommandArticles;
use App\Repository\CommandArticlesRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class CommandsController extends AbstractController
{

    #[Route('/command', name: 'app_commands')]
    public function index(BasketService $basketService, UtilisateurRepository $userRep): Response
    {

        $connectedUser = $userRep->find(32);
        $basketData = $basketService->getCartItems(32);
        $basketItemsCount = count($basketData);

        $totalPrice = array_reduce($basketData, function ($total, $product) {
            return $total + $product->getIdArticle()->getArtprix();
        }, 0);

        return $this->render('commands/command.html.twig', [
            'controller_name' => 'CommandsController',
            'basketData' => $basketData,
            'totalPrice' => $totalPrice,
            'connectedUser' => $connectedUser,
            'basketItemsCount' => $basketItemsCount,
        ]);
    }

    #[Route('/confirmCommand/{livMethod}/{payMethod}', name: 'app_confirmCommand')]
    public function ajoutCommand(
        CommandsRepository $commandsRepository,
        UtilisateurRepository $userRep,
        BasketService $basketService,
        CommandArticlesRepository $commandArticlesRep,
        $livMethod,
        $payMethod
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

        // add flash message
        $this->addFlash('success', 'Commande effectuée avec succès');
        if($payMethod == 'Cash'){
            $basketService->emptyCart(32);
            return $this->redirectToRoute('display_prod_front');
        }

        else{
            return $this->redirectToRoute('app_stripe');
        }
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
    public function afficheCommand(CommandsRepository $rep, $idCommand, CommandArticlesRepository $commandArticlesRep, CommandService $commandServ, BasketService $basketService): Response
    {
        $numCommand = $commandServ->generateOrderNumber($idCommand);
        $command = $rep->find($idCommand);

        $commandArticles = $commandArticlesRep->findBy(['command' => $idCommand]);

        $basketItemsCount= count($basketService->getCartItems(32));


        return $this->render('commands/affichageCommand.html.twig', [
            'controller_name' => 'CommandsController',
            'command' => $command,
            'numCommand' => $numCommand,
            'commandArticles' => $commandArticles,
            'basketItemsCount' => $basketItemsCount,
        ]);
    }

    #[Route('/afficheCommandAdmin/{idCommand}', name: 'app_afficheCommandAdmin')]
    public function afficheCommandAdmin(CommandsRepository $rep, $idCommand, CommandArticlesRepository $commandArticlesRep, CommandService $commandServ): Response
    {
        $numCommand = $commandServ->generateOrderNumber($idCommand);
        $command = $rep->find($idCommand);

        $commandArticles = $commandArticlesRep->findBy(['command' => $idCommand]);

        return $this->render('commands/affichageCommandAdmin.html.twig', [
            'controller_name' => 'CommandsController',
            'command' => $command,
            'numCommand' => $numCommand,
            'commandArticles' => $commandArticles,
        ]);
    }


    #[Route('/commandHistory', name: 'app_commandHistory')]
    public function commandHistory(CommandsRepository $rep, BasketService $basketService): Response
    {
        $Encourslist = [];
        $EnAttentelist = [];
        $Livrelist = [];
        $Annulelist = [];
        
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
        $basketItemsCount= count($basketService->getCartItems(32));

        return $this->render('commands/listCommandsClient.html.twig', [
            'controller_name' => 'CommandsController',
            'commands' => $commands,
            'Encourslist' => $Encourslist,
            'EnAttentelist' => $EnAttentelist,
            'Livrelist' => $Livrelist,
            'Annulelist' => $Annulelist,
            'basketItemsCount' => $basketItemsCount
        ]);
    }


    #[Route('/removeCommand/{idCommand}', name: 'app_removeCommand')]
    public function removeCommand($idCommand, CommandsRepository $commandRep, CommandArticlesRepository $commandArticlesRep)
    {
        $commandArticles = $commandArticlesRep->findBy(['command' => $idCommand]);
        foreach ($commandArticles as $commandArticle) {
            $commandArticlesRep->remove($commandArticle, true);
        }
        $command = $commandRep->find($idCommand);

        if (!$command) {
            throw new \Exception('Article not found');
        }

        $commandRep->remove($command, true);

        return $this->redirectToRoute('app_backCommand');
    }


    #[Route('/updateCommand/{idCommand}/{status}', name: 'app_updateCommand')]
    public function updateCommand($idCommand, $status, CommandsRepository $commandRep, Request $request)
    {
        $command = new commands();
        $command = $commandRep->find($idCommand);
        $command->setEtatCommande($status);
        
        $commandRep->save($command, true);

        // add flash message
        $this->addFlash('SuccessModifierCommand', 'Commande modifié avec succès');

        return $this->redirectToRoute('app_backCommand');
    }

    #[Route('/facture/{idCommand}', name: 'app_facture')]
    public function facture($idCommand, CommandsRepository $rep, CommandArticlesRepository $commandArticlesRep, CommandService $commandServ, BasketService $basketService ): Response
    {

        $numCommand = $commandServ->generateOrderNumber($idCommand);
        $command= $rep->find($idCommand);
        $commandArticles = $commandArticlesRep->findBy(['command' => $idCommand]);

        return $this->render('facture/facture.html.twig', [
            'controller_name' => 'CommandsController',
            'command' => $command,
            'commandArticles' => $commandArticles,
            'numCommand' => $numCommand,
        ]);
    }

    #[Route('/chart', name: 'app_chart')]
    public function commandsChart( CommandService $commandServ,\App\Repository\ArticleRepository $repo, EntityManagerInterface $em, CommandsRepository $comRep,  UtilisateurRepository  $userRep)
    {
        $listUsers=$userRep->findAll();
        $listArticles= $repo->findAll();

        $months = [
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December'
        ];

        $chartData = [];
        $totale=0;
        $commands = $comRep->findAll();
        for($i=1; $i<13; $i++){
            for($j=0; $j<count($commands); $j++){
                if($commands[$j]->getDateCommande()->format('m') == $i){
                    $totale += $commands[$j]->getCoutTotale();
                }
            }
            $chartData[$months[$i-1]] = $totale;
            $totale=0;
        }

        $totalThisMonth = $comRep->getTotalPriceOfCurrentMonth();
        $list = $comRep->findAll();



        $total = $repo->countByCatLib('personnes') +
            $repo->countByCatLib('classique') +
            $repo->countByCatLib('paysages');


        $BMWCount = $repo->countByCatLib('personnes');
        $MercedesCount = $repo->countByCatLib('classique');
        $AudiCount = $repo->countByCatLib('paysages');


        $BmwPercentage = round(($BMWCount / $total) * 100);
        $MercedesPercentage = round(($MercedesCount / $total) * 100);
        $AudiPercentage = round(($AudiCount / $total) * 100);

        return $this->render('chart/index.html.twig', [
            'chartData' =>  $chartData,
            'totalThisMonth' => $totalThisMonth,
            'list' => $list,
            'listUsers' => $listUsers,
            'BMWPercentage' => $BmwPercentage,
            'MercedesPercentage' => $MercedesPercentage,
            'AudiPercentage' => $AudiPercentage,
            'listArticles' =>$listArticles
        ]);
    }
}