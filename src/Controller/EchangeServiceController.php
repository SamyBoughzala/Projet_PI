<?php

namespace App\Controller;

use App\Entity\EchangeService;
use App\Form\EchangeServiceType;
use App\Repository\EchangeServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
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

    #[Route('/new', name: 'app_echange_service_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $echangeService = new EchangeService();
        $form = $this->createForm(EchangeServiceType::class, $echangeService);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($echangeService);
            $entityManager->flush();

            return $this->redirectToRoute('app_echange_service_index', [], Response::HTTP_SEE_OTHER);
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
}
