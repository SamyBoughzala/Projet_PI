<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminContollerController extends AbstractController
{
    #[Route('/admin/home', name: 'app_admin_home')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    #[Route('/admin/services', name: 'app_admin_services')]
    public function services(): Response
    {
        return $this->render('admin/services.html.twig');
    }

    #[Route('/admin/categories', name: 'app_admin_categories')]
    public function categories(): Response
    {
        return $this->render('admin/categories.html.twig');
    }

    #[Route('/admin/produits', name: 'app_admin_produits')]
    public function produits(): Response
    {
        return $this->render('admin/produits.html.twig');
    }

    #[Route('/admin/reclamations', name: 'app_admin_reclamations')]
    public function reclamations(): Response
    {
        return $this->render('admin/reclamations.html.twig');
    }

    #[Route('/admin/messages', name: 'app_admin_messages')]
    public function messages(): Response
    {
        return $this->render('admin/messages.html.twig');
    }

    #[Route('/admin/commandes', name: 'app_admin_commandes')]
    public function commandes(): Response
    {
        return $this->render('admin/commandes.html.twig');
    }

    #[Route('/admin/utilisateurs', name: 'app_admin_utilisateurs')]
    public function utilisateurs(): Response
    {
        return $this->render('admin/utilisateurs.html.twig');
    }
}
