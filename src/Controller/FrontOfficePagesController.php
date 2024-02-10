<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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

    #[Route('/contact', name: 'contact')]
    public function contact(): Response
    {
        return $this->render('front_office_pages/contactUs.html.twig');
    }

    #[Route('/panier', name: 'panier')]
    public function panier(): Response
    {
        return $this->render('front_office_pages/panier.html.twig');
    }

}
