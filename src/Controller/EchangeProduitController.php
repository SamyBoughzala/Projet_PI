<?php

namespace App\Controller;

use App\Entity\EchangeProduit;
use App\Form\EchangeProduitType;
use App\Repository\EchangeProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('echange/produit')]
class EchangeProduitController extends AbstractController
{
    #[Route('/', name: 'app_echange_produit_index', methods: ['GET'])]
    public function index(EchangeProduitRepository $echangeProduitRepository): Response
    {
        return $this->render('echange_produit/index.html.twig', [
            'echange_produits' => $echangeProduitRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_echange_produit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $echangeProduit = new EchangeProduit();
        $form = $this->createForm(EchangeProduitType::class, $echangeProduit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($echangeProduit);
            $entityManager->flush();

            return $this->redirectToRoute('app_echange_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('echange_produit/new.html.twig', [
            'echange_produit' => $echangeProduit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_echange_produit_show', methods: ['GET'])]
    public function show(EchangeProduit $echangeProduit): Response
    {
        return $this->render('echange_produit/show.html.twig', [
            'echange_produit' => $echangeProduit,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_echange_produit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EchangeProduit $echangeProduit, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EchangeProduitType::class, $echangeProduit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_echange_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('echange_produit/edit.html.twig', [
            'echange_produit' => $echangeProduit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_echange_produit_delete', methods: ['POST'])]
    public function delete(Request $request, EchangeProduit $echangeProduit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$echangeProduit->getId(), $request->request->get('_token'))) {
            $entityManager->remove($echangeProduit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_echange_produit_index', [], Response::HTTP_SEE_OTHER);
    }
}
