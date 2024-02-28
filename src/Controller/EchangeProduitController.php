<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Utilisateur;
use App\Entity\EchangeProduit;
use App\Form\EchangeProduitType;
use App\Repository\EchangeProduitRepository;
use App\Repository\ProduitRepository;
use App\Repository\UtilisateurRepository;
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

    #[Route('/new/{id}', name: 'app_echange_produit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, int $id, ProduitRepository $produitRepository, UtilisateurRepository $utilisateurRepository): Response
    {
        $user = $this->getUser();
        $selectedProduct = $produitRepository->find($id);
        $userProducts = $produitRepository->findBy(['utilisateur' => $user]);
        $echangeProduit = new EchangeProduit();
        $echangeProduit->setProduitIn($selectedProduct);
        $form = $this->createForm(EchangeProduitType::class, $echangeProduit, [
            'userProducts' => $userProducts,
            'selectedProduct' => $selectedProduct,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($echangeProduit);
            $entityManager->flush();
            return $this->redirectToRoute('app_echange_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('echange_produit/new.html.twig', [
            'selectedProduct' => $selectedProduct,
            'userProducts' => $userProducts,
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
    public function edit(EchangeProduit $echangeProduit, Request $request, EntityManagerInterface $entityManager, ProduitRepository $produitRepository): Response
    {
        $user = $this->getUser();
        $userProducts = $produitRepository->findBy(['utilisateur' => $user]);
        $selectedProduct = $echangeProduit->getProduitIn();

        $form = $this->createForm(EchangeProduitType::class, $echangeProduit, [
            'userProducts' => $userProducts,
            'selectedProduct' => $selectedProduct,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_echange_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('echange_produit/edit.html.twig', [
            'echange_produit' => $echangeProduit,
            'userProducts' => $userProducts,
            'form' => $form->createView(),
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

    #[Route('/{id}/transactions', name: 'app_echange_produit_transactions')]
    public function transactions(EchangeProduitRepository $echangeProduitRepository, ProduitRepository $produitRepository): Response
    {
        // Get the current user
        $user = $this->getUser();
        // Fetch user's products
        $userProducts = $produitRepository->findBy(['utilisateur' => $user]);
        $transactions = [];
        foreach ($userProducts as $product) {
            $productTransaction = $echangeProduitRepository->findBy(['produitIn' => $product]);
            $transactions = array_merge($transactions, $productTransaction);
        }
        
        return $this->render('echange_produit/transactions.html.twig', [
            'transactions' => $transactions,
        ]);
    }
}
