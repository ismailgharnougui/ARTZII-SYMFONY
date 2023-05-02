<?php

namespace App\Controller;

use App\Entity\Demande;
use App\Entity\Reclamation;
use App\Services\TwilioService;
use Twilio\Rest\Client;

use Knp\Component\Pager\PaginatorInterface;
use App\Entity\Service;
use App\Form\ServiceType;
use App\Repository\DemandeRepository;
use App\Repository\ReclamationRepository;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Notifier\Message\SmsMessage;
use Symfony\Component\Notifier\TexterInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


#[Route('admin/service')]
class ServiceController extends AbstractController
{
    #[Route('/', name: 'app_service_index', methods: ['GET'])]
    public function index(ServiceRepository $serviceRepository): Response
    {
        return $this->render('service/index.html.twig', [
            'services' => $serviceRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_service_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ServiceRepository $serviceRepository): Response
    {
        $service = new Service();
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $serviceRepository->save($service, true);

            return $this->redirectToRoute('app_service_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('service/new.html.twig', [
            'service' => $service,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_service_show', methods: ['GET'])]
    public function show(Service $service): Response
    {
        return $this->render('service/show.html.twig', [
            'service' => $service,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_service_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Service $service, ServiceRepository $serviceRepository): Response
    {
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $serviceRepository->save($service, true);

            return $this->redirectToRoute('app_service_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('service/edit.html.twig', [
            'service' => $service,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_service_delete', methods: ['POST'])]
    public function delete(Request $request, Service $service, ServiceRepository $serviceRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$service->getId(), $request->request->get('_token'))) {
            $serviceRepository->remove($service, true);
        }

        return $this->redirectToRoute('app_service_index', [], Response::HTTP_SEE_OTHER);
    }


    public function frontList(Request $request,ServiceRepository $serviceRepository,PaginatorInterface $paginator): Response
    {
        $services = $serviceRepository
            ->createQueryBuilder('p')
            ->where('p.user != :user')
            ->setParameter('user', $this->getUser())
            ->getQuery()
            ->getResult();

        $pagination = $paginator->paginate(
            $services,
            $request->query->getInt('page', 1), // Get the page parameter from the request (default to 1)
            3 // Items per page
        );

        return $this->render('service/frontList.html.twig', [
            'pagination' => $pagination,
        ]);

    }
    public function createService(Request $request, ServiceRepository $serviceRepository): Response
    {
        $service = new Service();
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $serviceRepository->save($service, true);


            $service->setUser($this->getUser());
            return $this->redirectToRoute('front_service_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('service/createService.html.twig', [
            'service' => $service,
            'form' => $form,
        ]);
    }



    public function deleteService(Request $request, EntityManagerInterface $entityManager)
    {
        $id = $request->get('id');

        $serviceRepository = $entityManager->getRepository(Service::class);
        $service = $serviceRepository->find($id);

        if (!$service) {
            throw $this->createNotFoundException('Service not found');
        }

        $entityManager->remove($service);
        $entityManager->flush();

        return $this->redirectToRoute('front_myService_index');
    }
    public function myfrontList(ServiceRepository $serviceRepository): Response
    {
        $services = $serviceRepository->findByUser($this->getUser());

        return $this->render('service/myfrontList.html.twig', [
            'services' => $services,
        ]);
    }

    /**
     * @Route("/services/demanded", name="front_demandedServices")
     */
    public function demandedServices(
        ServiceRepository $serviceRepository,
        DemandeRepository $demandeRepository,
    ): Response {
        $user = $this->getUser();
        $demandes = $demandeRepository->findBy(['demandeur' => $user]);
        $serviceIds = [];
        foreach ($demandes as $demande) {
            $serviceIds[] = $demande->getService()->getId();
        }

        if (empty($serviceIds)) {
            // No demanded services were found, so return an empty response or redirect
            // to a relevant page
            return $this->render('service/noDemanded.html.twig');
        }
        $services = $serviceRepository->findByIds($serviceIds);

        return $this->render('service/frontDemanded.html.twig', [
            'services' => $services,

        ]);
    }
    #[Route('/services/{id}/demander', name: 'front_demander_service')]
    public function demander(TexterInterface $texter,FlashyNotifier $flashy,Service $service, Request $request,DemandeRepository $demandeRepository): RedirectResponse
    {

        // check if user has already demanded this service
        $demande = $demandeRepository->findOneBy([
            'demandeur' => $this->getUser(),
            'service' => $service,
        ]);

        if ($demande !== null) {
            // user has already demanded this service
            $flashy->error('Vous avez déjà demandé ce service.','http://localhost:8000/');

        } else {
            // user has not yet demanded this service, create a new demande object
            $demande = new Demande();
            $demande->setDemandeur($this->getUser())
                ->setService($service)
                ->setDate(new \DateTime());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($demande);
            $entityManager->flush();
            $twilio_number = "+15017122661";
            $sid    = "AC1ce4fb70a525baba8610afa6e3d537bd";
            $token  = "2b094d8da16a553b865e01c0eab32994";
            $twilio = new Client($sid, $token);
            $message = $twilio->messages
                ->create("+21655244199", // to
                    array(
                        "from" => "+16813217756",
                        "body" => 'votre demande au service '.$service->getTitre().' est bien enregistré'
                    )
                );


            $flashy->success('Votre demande a été enregistrée.','http://localhost:8000/');
        }




        // redirect back to the service page
        return new RedirectResponse($request->headers->get('referer'));
    }


    #[Route('/services/{id}/reclamer/{description}', name: 'front_reclamer_service')]
    public function reclamerService($description,Service $service, Request $request,ReclamationRepository $reclamationRepository): RedirectResponse
    {



            $reclamation = new Reclamation();
        $reclamation->setUser($this->getUser())
                ->setSujet("[Service] : ".$service->getTitre())
                ->setDescription($description)

                ->setDateAjout(new \DateTime());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reclamation);
            $entityManager->flush();

            $this->addFlash('success', 'Votre reclamation a été enregistrée.');





        // redirect back to the service page
        return new RedirectResponse($request->headers->get('referer'));
    }

    public function frontshow(Service $service): Response
    {
        return $this->render('service/frontshow.html.twig', [
            'service' => $service,
        ]);
    }
}
