<?php

namespace App\Controller;

use App\Entity\EchangeService;
use App\Form\EchangeServiceType;
use App\Repository\EchangeServiceRepository;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/echange/service')]
class EchangeServiceController extends AbstractController
{
    #[Route('/', name: 'app_echange_service_index', methods: ['GET'])]
    public function index(EchangeServiceRepository $echangeServiceRepository): Response
    {
        return $this->render('echange_service/index.html.twig', [
            'echange_services' => $echangeServiceRepository->findAll(),
        ]);
    }

    #[Route('/{id}/new', name: 'app_echange_service_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,$id,ServiceRepository $serviceRepository): Response
    {
        $user = $this->getUser();
        $selectedService =$serviceRepository->find($id);
        $userServices = $serviceRepository->findBy(['utilisateur' => $user]);
        $echangeService = new EchangeService();
        $echangeService->setServiceIn($selectedService);
        $form = $this->createForm(EchangeServiceType::class, $echangeService, [
            'userServices' => $userServices,
            'selectedService' => $selectedService,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($echangeService);
            $entityManager->flush();
            return $this->redirectToRoute('app_echange_service_transactions', ['id' => 1], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('echange_service/new.html.twig', [
            'echange_service' => $echangeService,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_echange_service_show', methods: ['GET'])]
    public function show(EchangeService $echangeService): Response
    {
        return $this->render('echange_service/show.html.twig', [
            'echange_service' => $echangeService,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_echange_service_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EchangeService $echangeService, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EchangeServiceType::class, $echangeService);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_echange_service_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('echange_service/edit.html.twig', [
            'echange_service' => $echangeService,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_echange_service_delete', methods: ['POST'])]
    public function delete(Request $request, EchangeService $echangeService, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$echangeService->getId(), $request->request->get('_token'))) {
            $entityManager->remove($echangeService);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_echange_service_index', [], Response::HTTP_SEE_OTHER);
    }

    
    #[Route('/{id}/transactions_service', name: 'app_echange_service_transactions')]
    public function transactions(echangeServiceRepository $echangeServiceRepository, ServiceRepository $serviceRepository): Response
    {
        // Get the current user
        $user = $this->getUser();
        // Fetch user's services
        $userServices = $serviceRepository->findBy(['utilisateur' => $user]);
        $transactions = [];
        foreach ($userServices as $service) {
            $serviceTransaction = $echangeServiceRepository->findBy(['serviceIn' => $service]);
            $transactions = array_merge($transactions, $serviceTransaction);
        }
        
        return $this->render('echange_produit/transactions.html.twig', [
            'transactions' => $transactions,
        ]);
    }

    #[Route('/{id}/transactions_service/validate', name: 'app_echange_service_transactions_validate')]
    public function validate(Request $request, EchangeService $echangeService,$id,EchangeServiceRepository $echangeServiceRepository,ManagerRegistry $managerRegistry)
    {
        $em = $managerRegistry->getManager();

        $echangeService = $echangeServiceRepository->find(['id' => $id]);

        if (!$echangeService) {
            throw $this->createNotFoundException('Echange service not found.');
        }

        $echangeService->setValide(true);
        $em->persist($echangeService);
        $em->flush();

        return $this->render('echange_service/transaction_validated.html.twig', [
            'echangeService' => $echangeService,
        ]);
    }
    
}