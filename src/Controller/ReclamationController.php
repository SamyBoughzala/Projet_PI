<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Entity\Reponse;
use App\Form\ReclamationType;
use App\Form\ReponseType;
use App\Repository\ReclamationRepository;
use App\Repository\ReponseRepository;
use App\Repository\UtilisateurRepository;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ReclamationController extends AbstractController
{
    #[Route('/reclamation', name: 'app_reclamation')]
    public function index(): Response
    {
        return $this->render('reclamation/index.html.twig', [
            'controller_name' => 'ReclamationController',
        ]);
    }

    #[Route('/reclamation/affiche', name: 'app_affiche_reclamation')]
    public function list(ReclamationRepository $autrepos, UtilisateurRepository $utilisateurRepository): Response
    {
        $utilisateur = $utilisateurRepository->find(1);
        $reclamations = $autrepos->findBy(["utilisateur" => $utilisateur]);
        return $this->render('reclamation/affiche.html.twig', [
            'objects' => $reclamations,
        ]);
    }


    #[Route('/show', name: 'show')]
    public function show(ReclamationRepository $reclamation): Response
    {
        $objects = $reclamation->findALL();
        return $this->render('reclamation/affiche.html.twig', [
            'controller_name' => 'ReclamationController',
            'objects' => $objects
        ]);
    }


    #[Route('/contact', name: 'contact')]
    public function add(ManagerRegistry $man, Request $request): Response
    {
        $em = $man->getManager(); //em : entity manager

        $aut = new Reclamation();
        $date = new \DateTime();
        $aut->setDate($date);

        $formx = $this->createForm(ReclamationType::class, $aut); //

        $formx->handleRequest($request);

        if ($formx->isSubmitted() && $formx->isValid()) {

            $em->persist($aut);
            $em->flush();

            return $this->redirectToRoute('app_affiche_reclamation');
        }


        return $this->renderForm('front_office_pages/contactUs.html.twig', ['form3A60' => $formx]);
    }

    #[Route('/edit/{id}', name: 'edit')]
    public function editaut(Request $request, ManagerRegistry $manager, $id, ReclamationRepository $autrep): Response
    {
        $em = $manager->getManager();

        $aut = $autrep->find($id);
        $form = $this->createForm(ReclamationType::class, $aut);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($aut);
            $em->flush();
            return $this->redirectToRoute('app_affiche_reclamation');
        }

        return $this->renderForm('reclamation/modifier.html.twig', [
            'author' => $aut,
            'form3A602' => $form,
        ]);
    }




    #[Route('/respond/{id}', name: 'respond')]
    public function respond(Request $request, ManagerRegistry $manager, $id, ReclamationRepository $reclamationRepository)
    {
        $em = $manager->getManager();

        $aut = $reclamationRepository->find($id);
        $reponse = new Reponse();
        $reponse->setReponse($aut);
        $form = $this->createForm(ReponseType::class, $reponse);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($reponse);
            $em->flush();
            return $this->redirectToRoute('app_admin_reclamations');
        }

        return $this->render('reponse/add.html.twig', [
            'form3A602' => $form->createView(),
        ]);
    }



    #[Route('/delet/{id}', name: 'delete')]
    public function deleteaut(Request $request, $id, ManagerRegistry $manager, ReclamationRepository $autrep): Response
    {
        $em = $manager->getManager();
        $aut = $autrep->find($id);

        $em->remove($aut);
        $em->flush();

        return $this->redirectToRoute('app_affiche_reclamation');
    }

    #[Route('/admin/reclamations/{id}/delete', name: 'suppression')]
    public function delete(Request $request, $id, ManagerRegistry $manager, ReclamationRepository $autrep): Response
    {
        $em = $manager->getManager();
        $aut = $autrep->find($id);

        $em->remove($aut);
        $em->flush();

        return $this->redirectToRoute('app_admin_reclamations');
    }
}
