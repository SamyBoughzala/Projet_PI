<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Entity\Reponse;
use App\Form\ReclamationType;
use App\Form\ReponseType;
use App\Repository\ReclamationRepository;
use App\Repository\ReponseRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
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

    #[Route('/reclamation/affiche/{id}', name: 'app_affiche_reclamation')]
    public function list(ReclamationRepository $autrepos, $id, UtilisateurRepository $utilisateurRepository, ReponseRepository $reponseRepository, Request $request): Response
    {
        $utilisateur = $utilisateurRepository->find(1);
        $reclamations = $autrepos->findBy(["utilisateur" => $utilisateur]);

        $reponse = $reponseRepository->findOneBy(['id' => $id]);

        return $this->render('reclamation/affiche.html.twig', [
            'objects' => $reclamations,
            'reponse' => $reponse,
        ]);
    }


    #[Route('/show', name: 'show')]
    public function show(ReclamationRepository $reclamation, Reponse $reponseRepository): Response
    {
        $complaints = $reclamation->findAll();
        $responseByComplaint = [];
        $objects = $reclamation->findALL();

        foreach ($objects as $object) {
            // Retrieve the response associated with each complaint
            $response = $reponseRepository->findOneBy(['reponse' => $object]);
            dump($response);
            $responseByComplaint[$object->getId()] = $response;
        }
        return $this->render('reclamation/affiche.html.twig', [
            'controller_name' => 'ReclamationController',
            'objects' => $objects,
            'responseByComplaint' => $responseByComplaint
        ]);
    }


    #[Route('/contact', name: 'contact', methods: ['GET', 'POST'])]
    public function add(ManagerRegistry $man, Request $request): Response
    {
        $em = $man->getManager(); //em : entity manager

        $aut = new Reclamation();
        $date = new \DateTime();
        $aut->setDate($date);

        $formx = $this->createForm(ReclamationType::class, $aut); //

        $formx->handleRequest($request);

        if ($formx->isSubmitted() && $formx->isValid()) {
            $aut->setStatus("Pending..");
            $em->persist($aut);
            $em->flush();

            return $this->redirectToRoute('app_affiche_reclamation', [], Response::HTTP_SEE_OTHER);
        }


        return $this->renderForm('front_office_pages/contactUs.html.twig', [
            'form3A60' => $formx,
            'aut' => $aut,
        ]);
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




    #[Route('/respond/{id}', name: 'respond', methods: ['GET', 'POST'])]
    public function respond(Request $request, ManagerRegistry $manager, $id, ReclamationRepository $reclamationRepository)
    {
        $em = $manager->getManager();

        $aut = $reclamationRepository->find($id);
        $reponse = new Reponse();
        $reponse->setReponse($aut);
        $form = $this->createForm(ReponseType::class, $reponse);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $aut->setStatus("Treated");
            $em->persist($reponse);
            $em->flush();
            return $this->redirectToRoute('app_admin_reclamations');
        }

        return $this->render('reponse/add.html.twig', [
            'aut' => $aut,
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



    // METIER

    #[Route('/searchReclamationx', name: 'searchReclamationx')]
    public function searchReclamationx(Request $request, NormalizerInterface $Normalizer, ReclamationRepository $sr)
    {
        $repository = $this->getDoctrine()->getRepository(Reclamation::class);
        $requestString = $request->get('searchValue');
        $reclamation = $sr->searchReclamation($requestString);
        $jsonContent = $Normalizer->normalize($reclamation, 'json', ['groups' => 'reclamation']);
        $retour = json_encode($jsonContent);
        return new Response($retour);
    }

    #[Route('/search', name: 'search_reclamation', methods: ['GET', 'POST'])]
    public function search(Request $request, ReclamationRepository $reclamationRepository): Response
    {
        $searchQuery = $request->query->get('q');

        // Perform the search based on the 'titreR'
        $reclamations = $reclamationRepository->findByTitreR($searchQuery);

        // Render the existing template with the search results
        return $this->render('reclamation/searchRESULT.html.twig', [
            'reclamations' => $reclamations,
        ]);
    }
}
