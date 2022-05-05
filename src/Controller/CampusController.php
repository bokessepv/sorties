<?php

namespace App\Controller;

use App\Repository\CampusRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CampusController extends AbstractController
{
    /**
     * @Route("/campus", name="app_campus")
     */
    public function campus(CampusRepository $campusRepository): Response
    {
        $campus = $campusRepository->findbyAll();

            return $this->render('campus/campus.html.twig', [
            'campus' => '$campus',
        ]);
    }
}
