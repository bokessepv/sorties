<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\SortieType;
use App\Repository\SortieRepository;
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
     * @Route("/", name="list")
     */
    public function list(SortieRepository $sortieRepository): Response
    {
        // récupere les sorties publies
        // on appelle une méthode personalisée

        $sorties = $sortieRepository->findByAll();

        return $this->render('sortie/list.html.twig', [
            'sorties' => $sorties
        ]);
    }

    /**
     * @Route("/details/{id}", name="details")
     */
    public function details(int $id, SortieRepository $sortieRepository): Response
    {
        $sortie = $sortieRepository->find($id);

        // si il n'existe pas en bdd
        if(!$sortie){
            throw $this->createNotFoundException("Cette sortie n\'existe pas");
        }

        return $this->render('sortie/details.html.twig', [
            'sortie'=>$sortie
        ]);
    }

    /**
     * @Route("/create", name="create")
     */
    public function create(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response
    {
        $sortie = new Sortie();
        $sortie->setDateHeureDebut(new \DateTime());


        $sortieForm = $this->createForm(SortieType::class, $sortie);

        $sortieForm->handleRequest($request);

        if($sortieForm->isSubmitted() && $sortieForm->isValid()) {
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
