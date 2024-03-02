<?php

namespace App\Controller;

use App\Entity\EchangeProduit;
use App\Entity\EchangeService;
use App\Repository\EchangeProduitRepository;
use App\Repository\EchangeServiceRepository;
use App\Repository\ProduitRepository;
use App\Repository\ServiceRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;


class FrontOfficePagesController extends AbstractController
{
    #[Route('/home', name: 'index')]
    public function index(): Response
    {
        return $this->render('front_office_pages/index.html.twig');
    }

    #[Route('/produits', name: 'produits')]
    public function produits(): Response
    {
        return $this->render('front_office_pages/produits.html.twig');
    }

    #[Route('/services', name: 'services')]
    public function services(): Response
    {
        return $this->render('front_office_pages/services.html.twig');
    }

    #[Route('/panier', name: 'panier')]
    public function panier(): Response
    {
        return $this->render('front_office_pages/panier.html.twig');
    }

    #[Route('/transactions', name: 'app_echange_produit_transactions')]
    public function transactions(
        EchangeProduitRepository $echangeProduitRepository,
        ProduitRepository $produitRepository,
        EchangeServiceRepository $echangeServiceRepository,
        ServiceRepository $serviceRepository
    ): Response {
        // Get the current user
        $user = $this->getUser();
        
        // Fetch user's products
        $userProducts = $produitRepository->findBy(['utilisateur' => $user]);
        $productTransactions = [];
        foreach ($userProducts as $product) {
            $productTransaction = $echangeProduitRepository->findBy(['produitIn' => $product]);
            $productTransactions = array_merge($productTransactions, $productTransaction);
        }
        $pendingProducts = [];
        foreach ($userProducts as $product) {
        $pendingProduct = $echangeProduitRepository->findBy(['produitOut' => $product]);
        $pendingProducts = array_merge($pendingProducts, $pendingProduct);
        }
        // Fetch user's services
        $userServices = $serviceRepository->findBy(['utilisateur' => $user]);
        $serviceTransactions = [];
        foreach ($userServices as $service) {
            $serviceTransaction = $echangeServiceRepository->findBy(['serviceIn' => $service]);
            $serviceTransactions = array_merge($serviceTransactions, $serviceTransaction);
        }
        $pendingServices = [];
        foreach ($userServices as $service) {
            $pendingService = $echangeServiceRepository->findBy(['serviceOut' => $service]);
            $pendingServices = array_merge($pendingServices, $pendingService);
        }
        
        
        return $this->render('front_office_pages/transactions.html.twig', [
            'productTransactions' => $productTransactions,
            'pendingProducts' => $pendingProducts,
            'serviceTransactions' => $serviceTransactions,
            'pendingServices' => $pendingServices,

        ]);
    }

    #[Route('/{id}/transactions/validate_produit', name: 'app_echange_produit_transactions_validate')]
    public function validate_produit( EchangeProduit $echangeProduit,$id,EchangeProduitRepository $echangeProduitRepository,ManagerRegistry $managerRegistry)
    {
        $em = $managerRegistry->getManager();

        $echangeProduit = $echangeProduitRepository->find(['id' => $id]);

        if (!$echangeProduit) {
            throw $this->createNotFoundException('Echange produit not found.');
        }

        $echangeProduit->setValide(true);
        $em->persist($echangeProduit);
        $em->flush();

        return $this->render('front_office_pages/transaction_validated.html.twig', [
            'echangeProduit' => $echangeProduit,
        ]);
    }

    #[Route('{id}/transactions/validate_produit/generate-pdf/', name: 'generate_pdf')]
    public function generatePdfAction2($id,EchangeProduitRepository $echangeProduitRepository): Response
    {
        // Create an instance of Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $dompdf = new Dompdf($options);
        $echangeProduit = $echangeProduitRepository->find(['id' => $id]);
        // Render the current template
        $html = $this->renderView('front_office_pages/transaction_service_validated.html.twig', [
            'id' => $id,
            'echangeService'=> $echangeProduit,
        ]);
        // Load HTML into Dompdf
        $dompdf->loadHtml($html);

        // Set paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        // Render the PDF
        $dompdf->render();

        // Output the generated PDF
        $pdfContent = $dompdf->output();
        
        // Return a response with the PDF content
        return new Response(
            $pdfContent,
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="document.pdf"',
            ]
        );
    }

    #[Route('/transactions/validate_service/{id}', name: 'app_echange_service_transactions_validate')]
    public function validate_service( EchangeService $echangeService,$id,EchangeServiceRepository $echangeServiceRepository,ManagerRegistry $managerRegistry)
    {
        $em = $managerRegistry->getManager();

        $echangeService = $echangeServiceRepository->find(['id' => $id]);

        if (!$echangeService) {
            throw $this->createNotFoundException('Echange service not found.');
        }

        $echangeService->setValide(true);
        $em->persist($echangeService);
        $em->flush();

        return $this->render('front_office_pages/transaction_service_validated.html.twig', [
            'echangeService' => $echangeService,
        ]);
    }


    #[Route('/transactions/validate_service/{id}/generate-pdf/', name: 'generate_pdf')]
    public function generatePdfAction($id,EchangeServiceRepository $echangeServiceRepository): Response
    {
        // Create an instance of Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $dompdf = new Dompdf($options);
        $echangeService = $echangeServiceRepository->find(['id' => $id]);
        // Render the current template
        $html = $this->renderView('front_office_pages/transaction_service_validated.html.twig', [
            'id' => $id,
            'echangeService'=> $echangeService,
        ]);
        // Load HTML into Dompdf
        $dompdf->loadHtml($html);

        // Set paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        // Render the PDF
        $dompdf->render();

        // Output the generated PDF
        $pdfContent = $dompdf->output();
        
        // Return a response with the PDF content
        return new Response(
            $pdfContent,
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="document.pdf"',
            ]
        );
    }       

}
