<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VilleController extends AbstractController
{
    /**
     * @Route("/ville", name="app_ville")
     */
    public function ville(): Response
    {
        return $this->render('ville/ville.html.twig', [
            'controller_name' => 'VilleController',
        ]);
    }
}
