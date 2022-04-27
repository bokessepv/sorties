<?php

namespace App\Controller;

use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function details(int $id): Response
    {
        return $this->render('sortie/details.html.twig', [
        ]);
    }

    /**
     * @Route("/create", name="create")
     */
    public function create(): Response
    {
        return $this->render('sortie/create.html.twig', [
        ]);
    }
}
