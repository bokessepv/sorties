<?php

namespace App\Controller;

use App\Repository\CampusRepository;
use App\Repository\ParticipantRepository;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
        $sorties = $sortieRepository->findAll();

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
