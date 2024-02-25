<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Service;
use App\Form\CommentaireType;
use App\Form\ServiceType;
use App\Repository\ServiceRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServiceController extends AbstractController
{
    #[Route('/services', name: 'services')]
    public function services(ServiceRepository $serviceRepository): Response
    {
        $services=$serviceRepository->findAll();
        
        return $this->render('front_office_pages/services.html.twig',[
            'services'=>$services
        ]);
    }

    #[Route('/service/{id}', name: 'service')]
    public function service(ManagerRegistry $man, ServiceRepository $serviceRepository, $id, Request $request): Response
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
    
            $commentaires= $service->getCommentaires();

            return $this->render('front_office_pages/service/show.html.twig',[
                'service'=>$service,
                'commentaires'=>$commentaires,
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
