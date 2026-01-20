<?php
// src/Controller/RegistrationController.php
namespace App\Controller;

use App\Entity\DemandeUser;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\DemandeUserRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use App\Security\AppAuthenticator;


class RegistrationController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response {
        // Si l'utilisateur est déjà connecté → redirection immédiate
        // if ($this->getUser()) {
        //     dd($this->getUser());
        //     $this->addFlash('info', 'Vous êtes déjà connecté.');
        //     return $this->redirectToRoute('dashboard'); // ou 'app_home', 'admin_dashboard'...
        // }

        // Récupère l'erreur de login s'il y en a une (null si pas d'erreur)
        $error = $authenticationUtils->getLastAuthenticationError();

        // Dernier username saisi (pratique pour le remettre dans le champ)
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    #[Route('/register', name: 'app_register')]
    public function register(
            Request $request,
            EntityManagerInterface $em,
            UserPasswordHasherInterface $passwordHasher
        ): Response {
        
        $user = new DemandeUser();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('profil')->getData(); // or $request->files->get('profile_picture')

            $userName = $form->get('username')->getData();
            if ($file) {
                // $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

                $safeFilename = $userName .  ".jpg";

                $destination = $this->getParameter('kernel.project_dir') 
                            . '/public/uploads/profiles';

                try {
                    $newFilename = $file->move(
                        $destination,          // target directory
                        $safeFilename          // final filename
                    )->getFilename();         // returns the moved file name

                    // Now you can save $newFilename or full path in database
                    $user->setProfil($newFilename);

                } catch (FileException $e) {
                    $this->addFlash('error', 'Impossible d\'uploader l\'image : ' . $e->getMessage());
                }
            }

            $user->setNom($form->get('nom')->getData());
            $user->setPrenoms($form->get('prenoms')->getData());
            $user->setProfession($form->get('profession')->getData());
            $user->setAdresse($form->get('adresse')->getData());
            $user->setNumeroTel($form->get('numero_tel')->getData());
            $user->setMail($form->get('mail')->getData());
            $user->setRoles(["ROLE_USER"]);

            $user->setPassword($form->get('password')->getData());

            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('dashboard');
    }

    return $this->render('log/base.html.twig', [
        'registrationForm' => $form->createView(),
    ]);
}

    #[Route('/users', name: 'app_users')]
    public function users(UserRepository $userModel): Response
    {
        $users = $userModel->findAll();
        dd($users);
        return $this->redirectToRoute('dashboard');
    }    

    #[Route('/demande', name: 'app_demande')]
    public function demande(DemandeUserRepository $demandeModel): Response
    {
        $users = $demandeModel->findAll();
        dd($users);
        return $this->redirectToRoute('dashboard');
    }    

     #[Route('/demande/accepter/{id}', name: 'demande_accepter', requirements: ['id' => '\d+'])]
    public function accepter(int $id,
     DemandeUserRepository $demandeModel, 
     EntityManagerInterface $em,
    //  UserPasswordHasherInterface $passwordHasher
     ): Response {
        // Rechercher le voyage par son ID
        // $user =  new User();
        // $demande = $demandeModel->find($id);
        // // dd($demande);

        // $user->setUserName($demande->getUserName());
        // $user->setNom($demande->getNom());
        // $user->setPrenoms($demande->getPrenoms());
        // $user->setProfil($demande->getProfil());
        // $user->setProfession($demande->getProfession());
        // $user->setAdresse($demande->getAdresse());
        // $user->setNumeroTel($demande->getNumeroTel());
        // $user->setMail($demande->getMail()
        // );
        // $user->setRoles($demande->getRoles());
        // $user->setPassword($passwordHasher->hashPassword(
        //     $user,
        //     $demande->getPassword()
        // ));

        // $em->persist($user);
        // $em->flush();

        return $this->redirectToRoute('app_users');
    }  

     #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('Tsy tratra!');
    }    
}
