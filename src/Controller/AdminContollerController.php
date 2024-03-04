<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function produits(PaginatorInterface $paginatorInterface , EntityManagerInterface $entityManagerInterface, Request $request, ProduitRepository $ProduitRepository , CategorieRepository $categorieRepository): Response
    {
        $valeur=$request->get('valeur');
        if($valeur){
            $category=$categorieRepository->find($valeur);
            $query = $entityManagerInterface->createQueryBuilder()
            ->select('p')->from('App:Produit', 'p')
                ->where('p.categorie = :cat')->setParameter('cat', $category) ->getQuery();
            $pagiantion= $paginatorInterface->paginate(
                $query,
                $request->query->getInt('page',1),
                5
            );
               return $this->render('admin/produits.html.twig', [            
                'product'=> $pagiantion,
                'categories'=> $categorieRepository->findAll()
                
            ]);
        }
            
        $query = $entityManagerInterface->createQueryBuilder()
        ->select('p')->from('App:Produit', 'p')->getQuery();

        $pagiantion= $paginatorInterface->paginate(
            $query,
            $request->query->getInt('page',1),
            5
        );
           return $this->render('admin/produits.html.twig', [            
            'product'=> $pagiantion,
            'categories'=> $categorieRepository->findAll()
            
        ]);
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
