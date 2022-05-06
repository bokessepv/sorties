<?php

namespace App\Controller;

use App\Entity\PropertySearch;
use App\Repository\CampusRepository;
use App\Repository\ParticipantRepository;
use App\Repository\SortieRepository;
use http\Env\Request;
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
      //  PaginatorInterface $paginator,
       // Request $request
    )
    {
        //Gestion des filtres
       /* $search = new PropertySearch();
        $form = $this->createForm(PropertySearch::class, $search);
        $form->handleRequest($request);
        */
        $campus = $campusRepository->findAll();
        $sorties = $sortieRepository->findAll();
        /*
        $properties = $paginator->paginate(
            $this->$campusRepository->findAllVisibleQuery($search),
            $this->$sortieRepository->findAllVisibleQuery($search),
            $request->query->getString('page')
        );
        */
        return $this->render('main/home.html.twig', [
            'sorties' => $sorties,
            'campus' => $campus,
        //    'form'=> $form->createView()
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
