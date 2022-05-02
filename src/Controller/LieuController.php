<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Form\LieuType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LieuController extends AbstractController
{
    /**
     * @Route("/create/lieu", name="create_lieu")
     */
    public function create(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response
    {
       $lieu = new Lieu();

       $lieuForm = $this->createForm(LieuType::class, $lieu);

       $lieuForm->handleRequest($request);

       if($lieuForm->isSubmitted() && $lieuForm->isValid()) {
           $entityManager->persist($lieu);
           $entityManager->flush();

           return $this->redirectToRoute('sortie_create', ['id'=> $lieu->getId()]);
       }

        return $this->render('lieu/create.html.twig', [
            'lieuForm' => $lieuForm->createView()
        ]);
    }
}
