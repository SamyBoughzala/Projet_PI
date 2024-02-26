<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\LigneCommandeRepository;
use App\Repository\ProduitRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProduitController extends AbstractController
{
    #[Route('/produit', name: 'app_produit')]
    public function index(): Response
    {
        return $this->render('produit/index.html.twig', [
            'controller_name' => 'ProduitController',
        ]);
    }
    #[Route('produits', name: 'produits')]
    public function list(ProduitRepository $ProduitRepository, ManagerRegistry $man, Request $request): Response
    {
        $em=$man->getManager();

        $Produit=new Produit();

        $form=$this->createForm(ProduitType::class, $Produit);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $image=$form->get('photo')->getData();
            if($image){

                $imageName =  bin2hex(random_bytes(10)) .'.'. $image->guessExtension();
                $image->move($this->getParameter('kernel.project_dir') . '/public/uploads/produits',
                $imageName);
                $Produit->setPhoto($imageName);
                $em->persist($Produit);
                $em->flush();
                return $this->redirectToRoute('produits');

            }



        }
       
        
        return $this->render('front_office_pages/produits.html.twig', [
            'pr'=>$ProduitRepository->findAll(),
            'formulaire'=>$form->createView(),
        ]);
    }
    #[Route('produit/add', name: 'add_produit')]
    public function add(ManagerRegistry $man, Request $request){

        $em=$man->getManager();

        $Produit=new Produit();

        $form=$this->createForm(ProduitType::class, $Produit);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $image=$form->get('photo')->getData();
            if($image){

                $imageName =  bin2hex(random_bytes(10)) .'.'. $image->guessExtension();
                $image->move($this->getParameter('kernel.project_dir') . '/public/uploads/produits',
                $imageName);
                $Produit->setPhoto($imageName);
                $em->persist($Produit);
                $em->flush();
                return $this->redirectToRoute('produits');

            }



        }

        return $this->renderForm('produit/add.html.twig', ['formulaire'=>$form]);

        
    }
    #[Route('produit/edit/{id}', name: 'edit')]
    public function update(ManagerRegistry $man, $id, ProduitRepository $prepo, Request $request){

        $em=$man->getManager();
        
        $Produit=$prepo->find($id) ;

        $form=$this->createForm(ProduitType::class, $Produit);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $image=$form->get('photo')->getData();
            if($image){

                $imageName =  bin2hex(random_bytes(10)) .'.'. $image->guessExtension();
                $image->move($this->getParameter('kernel.project_dir') . '/public/uploads/produits',
                $imageName);
                $Produit->setPhoto($imageName);
                $em->persist($Produit);
                $em->flush();
            }

            $em->persist($Produit);
            $em->flush();

            return $this->redirectToRoute('produits');

        }

        return $this->renderForm('produit/modifier.html.twig', [
            'formX'=>$form,
            'pr'=>$Produit
        ]);
   
    }
    #[Route('produit/delete/{id}', name: 'delete')]
    public function delete(ManagerRegistry $man, $id, LigneCommandeRepository $ligneCommandeRepository, ProduitRepository $produitrepo){

        $em=$man->getManager();

        $Produit=$produitrepo->find($id) ;
        $lignes=$ligneCommandeRepository->findBy(["produit"=>$Produit]);
        if(count($lignes)>0){
            foreach($lignes as $ligne){
                $em->remove($ligne);
                $em->flush();
            }
        }

        $em->remove($Produit);
        $em->flush();

        return $this->redirectToRoute('produits');

    }
    #[Route('produit/add', name: 'add_produit')]
    public function addphoto(ManagerRegistry $man, Request $request){
        $em= $man->getManager();//créer un entity manager
        $Produit= new Produit();

        $form= $this->createForm(ProduitType::class, $Produit);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $image=$form->get('photo')->getData();
            if($image){

                $imageName =  bin2hex(random_bytes(10)) .'.'. $image->guessExtension();
                $image->move($this->getParameter('kernel.project_dir') . '/public/uploads/produits',
                $imageName);
            }

            $Produit->setPhoto($imageName);
            $em->persist($Produit);
            $em->flush();

            return $this->redirectToRoute('produits');
        }

        return $this->render("produit/add.html.twig", ["formulaire"=>$form->createView()]);
       
    }
    #[Route('/produit/{id}', name: 'details')]
    public function show(ProduitRepository $prrepository, $id): Response
    {
        return $this->render('produit/showDetails.html.twig', [
            'oneproduct' =>  $prrepository->find($id),
        ]);
    }


    #[Route('/admin/produits/{id}/delete', name: 'app_admin_produits_delete')]
    public function produitsdelete(ManagerRegistry $man, $id,LigneCommandeRepository $ligneCommandeRepository, ProduitRepository $produitrepo){

        $em=$man->getManager();

        $Produit=$produitrepo->find($id) ;
        $lignes=$ligneCommandeRepository->findBy(["produit"=>$Produit]);
        if(count($lignes)>0){
            foreach($lignes as $ligne){
                $em->remove($ligne);
                $em->flush();
            }
        }
        

        $em->remove($Produit);
        $em->flush();

        return $this->redirectToRoute('app_admin_produits');
    }
}
