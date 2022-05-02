<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Form\SortieType;
use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/sorties", name="sortie_")
 */
class SortieController extends AbstractController
{

    /**
     * @Route("/", name="accueil")
     */
    public function accueil(SortieRepository $sortieRepository): Response
    {
        // récupere les sorties publies
        // on appelle une méthode personalisée

        $sorties = $sortieRepository->findByAll();

        return $this->render('sortie/accueil.html.twig', [
            'sorties' => $sorties
        ]);
    }

    /**
     * @Route("/details/{id}", name="details")
     */
    public function details(int $id, SortieRepository $sortieRepository): Response
    {
        $sortie = $sortieRepository->find($id);
//        ajout d'un participant a la sortie (
        $sortie->addParticipant($this->getUser());


        // si il n'existe pas en bdd
        if(!$sortie){
            throw $this->createNotFoundException("Cette sortie n\'existe pas");
        }

        return $this->render('sortie/details.html.twig', [
            'sortie'=>$sortie,
            'participants'=>$sortie->getParticipants()->count(),

        ]);
    }

    /**
     * @Route("/create", name="create")
     */
    public function create(
        Request $request,
        EntityManagerInterface $entityManager,
        VilleRepository $villeRepository,
        EtatRepository $etatRepository
    ): Response
    {
        $sortie = new Sortie();

        $participant = $this->getUser();

        $campusOrganisateur = $this->getUser()->getCampus();

        $sortieForm = $this->createForm(SortieType::class, $sortie);

        $sortieForm->handleRequest($request);

        if($sortieForm->isSubmitted() && $sortieForm->isValid()) {

            $ville = $villeRepository->find(1);

            $lieu = new Lieu();
           $lieu->setNom('Bowling');
           $lieu->setRue('1 rue de la pierre');
           $lieu->setLongitude('56');
           $lieu->setLatitude('56');
           $lieu->setVille($ville);




           $entityManager->persist($lieu);
           $entityManager->flush();

           $sortie->setLieu($lieu);
            $etatCreation = $etatRepository->find(1);
            $sortie->setEtat($etatCreation);

            $sortie->setDateHeureDebut(new \DateTime());

            $sortie->setParticipant($participant);


            $sortie->setCampus($campusOrganisateur);

            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash('Bravo', 'Sortie ajoutés !');
            return $this->redirectToRoute('sortie_details', ['id'=> $sortie->getId()]);
        }



        return $this->render('sortie/create.html.twig', [
            'sortieForm' => $sortieForm->createView()
        ]);
    }
}
