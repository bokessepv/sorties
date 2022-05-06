<?php

namespace App\Controller;

use App\Entity\Ville;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin/ville", name="app_ville")
     */
    public function adminVille(
        VilleRepository $villeRepository,
        Request $request,
        EntityManagerInterface $entityManagerInterface
    ): Response
    {

        $ville = $villeRepository->findAll();

        return $this->render('admin/ville.html.twig', [
            'ville' => $ville,
        ]);

        // ajouter une ville

        $ville = new Ville();

    }
}
