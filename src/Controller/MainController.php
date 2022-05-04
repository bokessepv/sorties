<?php

namespace App\Controller;

use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/home", name="main_home")
     */
    public function home(SortieRepository $sortieRepository): Response
    {
        // récupere les sorties publies
        // on appelle une méthode personalisée

        $sorties = $sortieRepository->findByAll();

        return $this->render('main/home.html.twig', [
            'sorties' => $sorties
        ]);
    }

}
