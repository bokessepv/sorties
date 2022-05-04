<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Form\LieuType;
use App\Form\SortieType;
use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



class SortieController extends AbstractController
{
    /**
     * @Route("/accueil/sortie", name="sortie_create")
     */
    public function createSortie(
        Request $request,
        EntityManagerInterface $entityManager,
        EtatRepository $etatRepository
    ): Response
    {
        $sortie = new Sortie();
        $lieu = new Lieu();

        $participant = $this->getUser();

        $campusOrganisateur = $this->getUser()->getCampus();

        $sortieForm = $this->createForm(SortieType::class, $sortie);
        $lieuForm = $this->createForm(lieuType::class, $lieu);

        $sortieForm->handleRequest($request);
        $lieuForm->handleRequest($request);

        if($sortieForm->isSubmitted() && $sortieForm->isValid()) {


            $etatCreation = $etatRepository->find(1);
            $sortie->setEtat($etatCreation);

            $sortie->setDateHeureDebut(new DateTime());
            $sortie->addParticipant($participant);
            $sortie->setCampusOrganisateur($campusOrganisateur);
            $sortie->setOrganisateur($this->getUser());

            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash('Bravo', 'Sortie ajoutés !');
            return $this->redirectToRoute('sortie_details', ['id'=> $sortie->getId()]);
        }

        if($lieuForm->isSubmitted() && $lieuForm->isValid()){
            $entityManager->persist($lieu);
            $entityManager->flush();

            $this->addFlash('Bravo', 'Lieu ajoutés');
            return $this->redirectToRoute('sortie_create');

        }



        return $this->render('sortie/create.html.twig', [
            'sortieForm' => $sortieForm->createView(),
            'lieuForm' => $lieuForm->createView(),
            'campus' => $this->getUser()->getCampus(),
        ]);
    }



    /**
     * @Route("/accueil/sortie/{id}", name="sortie_update")
     */
    public function updateSortie(int $id,
        Request $request,
        EntityManagerInterface $entityManager,
        SortieRepository $sortieRepository,
        EtatRepository $etatRepository
    ): Response
    {
        $sortie = $sortieRepository->find($id);

        $lieu = $sortie->getLieu();

        $participant = $this->getUser();

        $campusOrganisateur = $this->getUser()->getCampus();

        $sortieForm = $this->createForm(SortieType::class, $sortie);
        $lieuForm = $this->createForm(lieuType::class, $lieu);

        $sortieForm->handleRequest($request);
        $lieuForm->handleRequest($request);

        if($sortieForm->isSubmitted() && $sortieForm->isValid()) {


            $etatCreation = $etatRepository->find(1);
            $sortie->setEtat($etatCreation);

            $sortie->setDateHeureDebut(new DateTime());
            $sortie->addParticipant($participant);
            $sortie->setCampusOrganisateur($campusOrganisateur);
            $sortie->setOrganisateur($this->getUser());

            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash('Bravo', 'Sortie modifiés !');
            return $this->redirectToRoute('sortie_details', ['id'=> $sortie->getId()]);
        }

        if($lieuForm->isSubmitted() && $lieuForm->isValid()){
            $entityManager->persist($lieu);
            $entityManager->flush();

            $this->addFlash('Bravo', 'Lieu ajoutés');
            return $this->redirectToRoute('sortie_create');

        }



        return $this->render('sortie/updateSortie.html.twig', [
            'sortieForm' => $sortieForm->createView(),
            'lieuForm' => $lieuForm->createView(),
            'campus' => $this->getUser()->getCampus(),
        ]);
    }

    /**
     * @Route("/accueil/details/{id}", name="sortie_details")
     */
    public function details(int $id, SortieRepository $sortieRepository): Response
    {
        $sortie = $sortieRepository->find($id);
//        ajout d'un participant a la sortie (


        // si il n'existe pas en bdd
        if(!$sortie){
            throw $this->createNotFoundException("Cette sortie n\'existe pas");
        }

        return $this->render('sortie/details.html.twig', [
            'sortie'=>$sortie,
            'participants'=>$sortie->getParticipants()->count(),

        ]);
    }

}
