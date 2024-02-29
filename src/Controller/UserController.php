<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\AuthentificationType;
use App\Form\InscriptionType;
use App\Form\ProfilType;
use App\Repository\UtilisateurRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\String\Slugger\AsciiSlugger;



use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\MailerInterface;

class UserController extends AbstractController
{

   



    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }



    #[Route('/inscription', name: 'app_user')]
    public function register(Request $request, ManagerRegistry $manager, UserPasswordHasherInterface $passwordHasher,MailerInterface $mailer): Response
{

    
    $user = new Utilisateur();
    $form = $this->createForm(InscriptionType::class, $user );
    $form->handleRequest($request);

    
    $random_bytes = random_bytes(10);
    // Convertir les octets en une chaîne de caractères ASCII valide
    $ascii_string = mb_convert_encoding($random_bytes, 'ASCII');
    // Utiliser AsciiSlugger avec la chaîne ASCII
    $code = (new AsciiSlugger())->slug($ascii_string)->toString();

    
    if($form->isSubmitted() && $form->isValid()) {

        $plainPassword = $form->get('mot_de_passe')->getData();

        $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
        $user->setMotDePasse($hashedPassword);

        $user->setRoles(['ROLE_USER']);

        $user->setAuthCode($code);
    
    $email = (new Email())
    ->from('Bensalahons428@gmail.com')
    ->to($user->getEmail())
    ->subject('Code d\'authentification pour SwapNshare')
    ->html('Votre code d\'authentification est : ' . $code);

    $mailer->send($email);

       
         
        $em= $manager->getManager();
        
        $em->persist($user);
        $em->flush();
      

        $userId = $user->getId();


    // Stocker le code dans l'entité Utilisateur
 
        return $this->redirectToRoute('app_login');
    }

    return $this->renderForm('user/inscrit.html.twig',[
        'form'=>$form, 

]);
}




/* #[Route('/authentification/{id}', name: 'app_authentification')]
public function connexion(Request $request, $id, UtilisateurRepository $utili): Response
{
    $form = $this->createForm( AuthentificationType::class);
    $form->handleRequest($request);
    $formData = $form->getData();

    $utilisateur= $utili->find($id);
        


    if ($form->isSubmitted() && $form->isValid()) {
        

              // Récupérer les valeurs du formulaire
        $email = $form->get('email')->getData();
        $motDePasse = $form->get('mot_de_passe')->getData();

        if($email=== $utilisateur->getEmail() && $motDePasse === $utilisateur->getMotDePasse()){

         
            return $this->redirectToRoute('app_user');



        }else{

            $errorMessage = "L'email et/ou le mot de passe sont incorrects.";
            return $this->render('user/authen.html.twig', [
                'form' => $form->createView(),
                'errorMessage' => $errorMessage,
            ]);
        }
    }

    return $this->render('user/authen.html.twig', [
        'form' => $form->createView(),
    ]);
}*/


   
#[Route('/D/{id}', name: 'app_delete')]
public function deleteau($id, ManagerRegistry $manager, UtilisateurRepository $rep ): Response
{
        $utilisateur= $rep->find($id);
        $em= $manager->getManager();
       $em->remove($utilisateur);
       $em->flush(); 
    


    return $this->redirectToRoute('app_admin_utilisateurs');
}
 

  
#[Route('/e/{id}', name: 'app_edit')]
public function editau(ManagerRegistry $manager,UtilisateurRepository  $utilisateurrep,$id,  Request $req): Response
{


        $em= $manager->getManager();
        $utili= $utilisateurrep->find($id); 
        
        $form=$this->createForm(ProfilType::class,$utili);

      $form->handleRequest( $req);

      if($form->isSubmitted()&& $form->isValid()){
          $em->persist($utili);
          $em->flush();
          
        $userId = $utili->getId();
           return $this->redirectToRoute('app_edit',  ['id' => $userId]); 

      }
        


    return $this->renderForm('user/Profil.html.twig',[
             'form'=>$form, 

    ]);
}


  
  

#[Route(path: '/loggin', name: 'app_login')]
public function login(AuthenticationUtils $authenticationUtils): Response
{
    // if ($this->getUser()) {
    //     return $this->redirectToRoute('target_path');
    // }

    // get the login error if there is one
    $error = $authenticationUtils->getLastAuthenticationError();
    // last username entered by the user
    $lastUsername = $authenticationUtils->getLastUsername();

    return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
}

#[Route(path: '/logout', name: 'app_logout')]


public function logout(SessionInterface $session): void
{   
    if ($session->has('user_id') ) {
        $session->remove('user_id');
        $session->remove('user_role');
        $session->remove('user_email');
        $session->remove('user_address');
        $session->remove('user_phonenumber');
        $session->remove('user_score');
        $session->remove('user_name');
    }

    throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
}



#[Route('/profile', name: 'app_profil')]
public function Profil(): Response
{
    return $this->render('user/profile.html.twig');
}




#[Route('/verify-code',name:'verify_code', methods:['GEt'])]
public function verificationCodeForm(): Response
{
    return $this->render('security/2fa_form.html.twig');
}



/*#[Route('/verify-code', name:'verify_code_submit', methods:['POST'])]
public function verifyCodeAction(Request $request, UrlGeneratorInterface  $urlGenerator)
{
    $codeSaisi = $request->request->get('code');

    $codeEnregistre = $request->getSession()->get('user_Auth');

    if ($codeSaisi === $codeEnregistre) {
        // Redirection si le code est correct
        return $this->redirectToRoute('app_profil');
    } else {
        // Gérer le cas où les codes ne correspondent pas
        // Vous pouvez rediriger vers une autre page, afficher un message d'erreur, etc.
        return $this->render('security/2fa_form.html.twig', [
            'error' => 'Le code est incorrect.'
        ]);
    }*/


}




