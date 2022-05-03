<?php

namespace App\Controller;

use App\Form\UpdateFormType;
use App\Entity\Participant;
use App\Form\RegistrationFormType;
use App\Security\AppAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, AppAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $user = new Participant();
        $user->setRoles(["ROLE_USER"]);

        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

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
    //Pour récupérer et afficher un profil
    /**
    * @Route("/profile", name="app_profile")
    */
    public function Profile($id): Response
    {
        $profile = $this->getDoctrine()->getRepository(Participant::class)->findById($id);

        return $this->render('registration/profil.html.twig', [
            "profile" => $profile,
        ]);
    }
    //Pour modifier son profil
    /**
     * @Route("/update", name="app_update")
     */
     public function Update(): Response
     {
         $form = $this-> createForm(UpdateFormType::class);
         return $this->render('registration/update.html.twig', [
             "titleForm" => "Mon profil",
             "updateForm" => $form->createView(),
         ]);
     }
}
