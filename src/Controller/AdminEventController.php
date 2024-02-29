<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Form\EvenementType;
use App\Repository\EvenementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class AdminEventController extends AbstractController
{
    #[Route('/event', name: 'app_event_index', methods: ['GET'])]
    public function eventIndex(EvenementRepository $evenementRepository): Response
    {
        return $this->render('admin/eventIndex.html.twig', [
            'evenements' => $evenementRepository->findAll(),
        ]);
    }

    #[Route('/event/{id}', name: 'app_event_show', methods: ['GET'])]
    public function eventShow(Evenement $evenement): Response
    {
        return $this->render('admin/eventShow.html.twig', [
            'evenement' => $evenement,
        ]);
    }

    #[Route('/event/{id}/edit', name: 'app_event_edit', methods: ['GET', 'POST'])]
    public function eventEdit(Request $request, Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_event_show', ['id' => $evenement->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/eventEdit.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);
    }

    #[Route('/event/{id}', name: 'app_event_delete', methods: ['POST'])]
    public function eventDelete(Request $request, Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$evenement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($evenement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/show-event-count', name: 'show_event_count')]
    public function showEventCount(EvenementRepository $evenementRepository): Response
    {
        $count = $evenementRepository->countEvents();

        return $this->render('show_event_count.html.twig', [
            'count' => $count,
        ]);
    }
}
