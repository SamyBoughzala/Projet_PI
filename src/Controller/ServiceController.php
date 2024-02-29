<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Service;
use App\Form\CommentaireType;
use App\Form\ServiceType;
use App\Repository\CategorieRepository;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServiceController extends AbstractController
{
    #[Route('/services', name: 'services')]
    public function services(EntityManagerInterface $em, PaginatorInterface $paginatorInterface,CategorieRepository $categorieRepository, Request $request): Response
    {
        $categories= $categorieRepository->findAll();
        $qb = $em->createQueryBuilder();
        $qb->select('s')->from("App:Service", 's');
        $query=$qb->getQuery();
        $pagination= $paginatorInterface->paginate(
            $query,
            $request->query->getInt('page', 1),
            2
        );
        
        
        return $this->render('front_office_pages/services.html.twig',[
            'categories'=>$categories,
            'pagination'=>$pagination
        ]);
    }

    #[Route('/services/{id}', name: 'FiltredServices')]
    public function filtreByCategoryServices($id, EntityManagerInterface $em, PaginatorInterface $paginatorInterface, ServiceRepository $serviceRepository, Request $request, CategorieRepository $categorieRepository): Response
    {
        $qb = $em->createQueryBuilder();
        $category= $categorieRepository->find($id);
        $categories= $categorieRepository->findAll();
        $qb->select('s')->from("App:Service", 's')->where('s.categorie = :cat')->setParameter('cat', $category);
        $query=$qb->getQuery();
        $pagination= $paginatorInterface->paginate(
            $query,
            $request->query->getInt('page', 1),
            2
        );
        
        return $this->render('front_office_pages/services.html.twig',[
            'categories'=>$categories,
            'pagination'=>$pagination
        ]);
    }

    #[Route('/service/{id}', name: 'service')]
    public function service(EntityManagerInterface $entityManagerInterface, ManagerRegistry $man, ServiceRepository $serviceRepository,PaginatorInterface $paginatorInterface, $id, Request $request): Response
    {
        $em= $man->getManager();

        $service=$serviceRepository->find($id);
        if($service!=null){
            $commentaire= new Commentaire();

            $form= $this->createForm(CommentaireType::class, $commentaire);
    
            $form->handleRequest($request);
    
            if($form->isSubmitted() && $form->isValid()){
                $commentaire->setService($service);
                $em->persist($commentaire);
                $em->flush();
    
                return $this->redirectToRoute('service',array('id'=>$id));
            }
    
            $qb = $entityManagerInterface->createQueryBuilder();
            $qb->select('c')->from("App:Commentaire", 'c')->where('c.service = :ser')->setParameter('ser', $service);
            $query=$qb->getQuery();
            $pagination= $paginatorInterface->paginate(
                $query,
                $request->query->getInt('page', 1),
                2
            );;

            return $this->render('front_office_pages/service/show.html.twig',[
                'service'=>$service,
                'pagination'=>$pagination,
                "formCommentaire"=>$form->createView()
            ]);
        }
    else{
        return $this->render('front_office_pages/service/serviceInexistant.html.twig'); 
    }

    }

    #[Route('/services/ajouter', name: 'add_service')]
    public function add(ManagerRegistry $man, Request $request){
        $em= $man->getManager();//créer un entity manager
        $service= new Service();

        $form= $this->createForm(ServiceType::class, $service);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $image=$form->get('photo')->getData();
            if($image){
                $imageName =  bin2hex(random_bytes(10)) .'.'. $image->guessExtension();
                $image->move(
                    'F:\ESPRIT\SwapNShare2\Projet_PI'. '/public/uploads/services',
                    $imageName
                );
            }

            $service->setPhoto($imageName);
            $em->persist($service);
            $em->flush();

            return $this->redirectToRoute('services');
        }

        return $this->render("front_office_pages/service/formulaireServiceAjout.html.twig", ["formServiceAjout"=>$form->createView()]);

    }

    #[Route('/service/modifier/{id}', name: 'modifier_service')]
    public function modifier(ManagerRegistry $man, $id, ServiceRepository $serviceRepository,  Request $request){
        $em= $man->getManager();//créer un entity manager
        $service= $serviceRepository->find($id);

        $form= $this->createForm(ServiceType::class, $service);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $image=$form->get('photo')->getData();
            if($image){
                $imageName =  bin2hex(random_bytes(10)) .'.'. $image->guessExtension();
                $image->move(
                    'F:\ESPRIT\SwapNShare2\Projet_PI'. '/public/uploads/services',
                    $imageName
                );
            }
            $service->setPhoto($imageName);
            $em->persist($service);
            $em->flush();

            return $this->redirectToRoute('services');
        }

        return $this->renderForm("front_office_pages/service/formulaireService.html.twig", ["formService"=>$form]);

    }

    #[Route('/service/supprimer/{id}', name: 'supprimer_service')]
    public function supprimer(ManagerRegistry $man, $id, ServiceRepository $serviceRepository){
        $em= $man->getManager();//créer un entity manager
        $service= $serviceRepository->find($id);

            $em->remove($service);
            $em->flush();

            return $this->redirectToRoute('services');

    }

    #[Route('admin/services/supprimer/{id}', name: 'suppression_service')]
    public function supprime(ManagerRegistry $man, $id, ServiceRepository $serviceRepository){
        $em= $man->getManager();//créer un entity manager
        $service= $serviceRepository->find($id);

            $em->remove($service);
            $em->flush();

            return $this->redirectToRoute('app_admin_services');

    }
}
