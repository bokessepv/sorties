<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\UpdateFormType;
use App\Repository\CampusRepository;
use App\Repository\ParticipantRepository;
use App\Repository\SortieRepository;
use App\Security\AppAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\String\Slugger\SluggerInterface;

class MainController extends AbstractController
{
    /**
     * @Route("/home", name="main_home")
     */
    public function home(
        SortieRepository $sortieRepository,
        CampusRepository $campusRepository
    ): Response
    {

        $campus = $campusRepository->findAll();
        $sorties = $sortieRepository->findByAll();


        return $this->render('main/home.html.twig', [
            'sorties' => $sorties,
            'campus' => $campus
        ]);
    }
    /**
     * @Route("/home/profilDetails{id}", name="main_profil_details")
     */
    public function profilDetails(
        int $id,
        ParticipantRepository $participantRepository,
        SortieRepository $sortieRepository
    ): Response
    {
        $participant = $participantRepository->find($id);
        return $this->render('main/profil.html.twig', [
            'participant' => $participant,
        ]);
    }


}
