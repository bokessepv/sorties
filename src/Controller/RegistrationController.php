<?php

namespace App\Controller;

use App\Form\UpdateFormType;
use App\Entity\Participant;
use App\Form\RegistrationFormType;
use App\Repository\ParticipantRepository;
use App\Security\AppAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        UserAuthenticatorInterface $userAuthenticator,
        AppAuthenticator $authenticator,
        SluggerInterface $slugger,
        EntityManagerInterface $entityManager
    ): Response
    {
        $user = new Participant();
        $user->setRoles(["ROLE_USER"]);
        $user->setAdministrateur(false);
        $user->setActif(true);

        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($form->get('photo')->getData() == null)
            {
                $user->setPhoto('eni_logo.png');
            }
            else{
                $imageFile = $form->get('photo')->getData();
                if ($imageFile)
                {

                    $originalImageName = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeImageName =$slugger->slug($originalImageName);
                    $imageName = $safeImageName.'-'.uniqid().'.'.$imageFile->guessExtension();
                    try {
                        $imageFile->move(
                            $this->getParameter('brochures_directory'),
                            $imageName);
                    }catch (FileException$e)
                    {
                        return $e->getTrace();
                    }
                    $user->setPhoto($imageName);
                }

            }

            $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }


    // Inscription Admin
    /**
     * @Route("/register/admin1233210", name="app_register_admin1233210")
     */
    public function registerAdmin(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        UserAuthenticatorInterface $userAuthenticator,
        AppAuthenticator $authenticator,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger
    ): Response
    {
        $user = new Participant();
        $user->setRoles(["ROLE_USER"]);
        $user->setAdministrateur(true);
        $user->setActif(true);

        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('photo')->getData() == null)
            {
                $user->setPhoto('eni_logo.png');
            }
            else{
                $imageFile = $form->get('photo')->getData();
                if ($imageFile)
                {

                    $originalImageName = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeImageName =$slugger->slug($originalImageName);
                    $imageName = $safeImageName.'-'.uniqid().'.'.$imageFile->guessExtension();
                    try {
                        $imageFile->move(
                            $this->getParameter('brochures_directory'),
                            $imageName);
                    }catch (FileException$e)
                    {
                        return $e->getTrace();
                    }
                    $user->setPhoto($imageName);
                }

            }

            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }


    //Pour modifier son profil
    /**
     * @Route("/profil{id}", name="app_profil")
     */
    public function updateProfile(
                                  Request                     $request,
                                  SluggerInterface            $slugger,
                                  EntityManagerInterface      $entityManager,
                                  AppAuthenticator            $authenticator,
                                  UserAuthenticatorInterface $userAuthenticator,
                                  UserPasswordHasherInterface $userPasswordHasher
    ): Response
    {
        $user = $this->getUser();

        $updateForm = $this->createForm(UpdateFormType::class, $user);
        $updateForm->handleRequest($request);

        if ($updateForm->isSubmitted() && $updateForm->isValid()) {
            $photoFile = $updateForm->get('photo')->getData();
            if ($photoFile) {
                $originalImageName = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeImageName = $slugger->slug($originalImageName);
                $imageName = $safeImageName . '-' . uniqid() . '.' . $photoFile->guessExtension();
                try {
                    $photoFile->move(
                        $this->getParameter('upload_photo_directory'),
                        $imageName);
                } catch (FileException$e) {
                    return new Response ($e->getMessage());
                }
                $user->setPhoto($imageName);

            }
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $updateForm->get('password')->getData())
            );

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Votre profil à bien été modifié');
            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );

        }

        return $this->render('registration/updateProfil.html.twig', [
            'updateForm' => $updateForm->createView(),
        ]);


    }

    /**
     * @Route("/delete/{id}", name="delete_profil")
     */
    public function deleteProfil(
        Participant $participant,
        EntityManagerInterface $entityManager,
        AuthenticationUtils $authenticationUtils
    )
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        $entityManager->remove($participant);
        $entityManager->flush();

        $this->addFlash('danger', 'Votre compte à bien été supprimé');
        return $this->render('security/login.html.twig', ['last_username' => $lastUsername,'error' => $error] );
    }

}
