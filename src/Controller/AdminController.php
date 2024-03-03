<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Enum\UsersRoles;
use App\Form\InscriptionType;
use App\Repository\UtilisateurRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
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

    #[Route('/admin/addadmin', name: 'app_addadmin')]
    public function addadmin(Request $request, UserPasswordHasherInterface $passwordHasher,MailerInterface $mailer,ManagerRegistry $manager): Response
    {


        $user = new Utilisateur();
        $form = $this->createForm(InscriptionType::class, $user );
        $form->handleRequest($request);
        $random_bytes = random_bytes(10);

        // Convert bytes to ASCII string
        $ascii_string = mb_convert_encoding($random_bytes, 'ASCII');

// Use AsciiSlugger to generate a slug
        $slug = (new \Symfony\Component\String\Slugger\AsciiSlugger())->slug($ascii_string)->toString();

// Extract alphanumeric characters only
        $alphanumeric_code = preg_replace('/[^a-zA-Z0-9]/', '', $slug);

// Take the first 6 characters
        $final_code = substr($alphanumeric_code, 0, 6);


        if($form->isSubmitted() && $form->isValid()) {

            $plainPassword = $form->get('mot_de_passe')->getData();

            $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
            $user->setMotDePasse($hashedPassword);

            $user->setRole(UsersRoles::ADMIN);
            $user->setImageName("téléchargement.png");
            $user->setAuthCode($final_code);
            $email = (new Email())
                ->from('Bensalahons428@gmail.com')
                ->to($user->getEmail())
                ->subject('Code d\'authentification pour SwapNshare')
                ->html('Votre code d\'authentification est : ' . $final_code);

            $mailer->send($email);
            $em= $manager->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('app_admin_utilisateurs');
        }

        return $this->renderForm('admin/addadmin.html.twig',[
            'form'=>$form,

        ]);
    }

    #[Route('/admin/utilisateurs', name: 'app_admin_utilisateurs')]
    public function utilisateurs(UtilisateurRepository $utilisateurs ): Response
    {

        return $this->render('admin/utilisateurs.html.twig', [
            'rep'=> $utilisateurs->findAll(),
        ]);
        
    }
}
