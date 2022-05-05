<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Entity\Serie;
use App\Entity\Sortie;
use App\Form\LieuType;
use App\Form\SortieType;
use App\Repository\CampusRepository;
use App\Repository\EtatRepository;
use App\Repository\LieuRepository;
use App\Repository\SortieRepository;
use App\Repository\VilleRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;


class SortieController extends AbstractController
{
    /**
     * @Route("/home/sortie", name="sortie_create")
     */
    public function createSortie(
        Request $request,
        EntityManagerInterface $entityManager,
        EtatRepository $etatRepository,
        SluggerInterface $slugger
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
            if(!$sortieForm->get('photo')->getData())
            {
                $sortie->setPhoto('logo_sortir.png');
            }
            $imageFile = $sortieForm->get('photo')->getData();
            if ($imageFile)
            {

                $originalImageName = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeImageName =$slugger->slug($originalImageName);
                $imageName = $safeImageName.'-'.uniqid().'.'.$imageFile->guessExtension();
                try {
                    $imageFile->move(
                        $this->getParameter('upload_photo_directory'),
                        $imageName);
                }catch (FileException$e)
                {
                    return new Response($e->getMessage()) ;
                }
                $sortie->setPhoto($imageName);
            }


            $etatCreation = $etatRepository->find(1);
            $sortie->setEtat($etatCreation);

            $sortie->addParticipant($participant);
            $sortie->setCampusOrganisateur($campusOrganisateur);
            $sortie->setOrganisateur($this->getUser());

            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash('success', 'Sortie ajoutés !');
            return $this->redirectToRoute('sortie_details', ['id'=> $sortie->getId()]);
        }

        if($lieuForm->isSubmitted() && $lieuForm->isValid()){
            $entityManager->persist($lieu);
            $entityManager->flush();

            $this->addFlash('success', 'Lieu ajoutés');
            return $this->redirectToRoute('sortie_create');

        }



        return $this->render('sortie/create.html.twig', [
            'sortieForm' => $sortieForm->createView(),
            'lieuForm' => $lieuForm->createView(),
            'campus' => $this->getUser()->getCampus(),

        ]);
    }



    /**
     * @Route("/home/sortie/{id}", name="sortie_update")
     */
    public function updateSortie(int $id,
        Request $request,
        EntityManagerInterface $entityManager,
        SortieRepository $sortieRepository,
        EtatRepository $etatRepository,
        SluggerInterface $slugger
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

            if(!$sortieForm->get('photo')->getData())
            {
                $sortie->setPhoto('logo_sortir.png');
            }
            $imageFile = $sortieForm->get('photo')->getData();
            if ($imageFile)
            {

                $originalImageName = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeImageName =$slugger->slug($originalImageName);
                $imageName = $safeImageName.'-'.uniqid().'.'.$imageFile->guessExtension();
                try {
                    $imageFile->move(
                        $this->getParameter('upload_photo_directory'),
                        $imageName);
                }catch (FileException$e)
                {
                    return new Response($e->getMessage()) ;
                }
                $sortie->setPhoto($imageName);
            }


            $etatCreation = $etatRepository->find(2);
            $sortie->setEtat($etatCreation);

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
            'sortie' => $sortie
        ]);
    }

    /**
     * @Route("/home/details/{id}", name="sortie_details")
     */
    public function detailsSortie(
        int $id,
        SortieRepository $sortieRepository,
        LieuRepository $lieuRepository,
        VilleRepository $villeRepository
    ): Response
    {

        $sortie = $sortieRepository->find($id);
        $lieuDeLaSortie = $sortie->getLieu();
        $lieu = $lieuRepository->find($lieuDeLaSortie);
        $ville = $villeRepository->find($lieu);
        $user = $this->getUser();



        if(!$sortie){
            throw $this->createNotFoundException("Cette sortie n\'existe pas");
        }

        return $this->render('sortie/details.html.twig', [
            'sortie' =>$sortie,
            'lieu' =>$lieu,
            'campus' => $this->getUser()->getCampus(),
            'ville'=>$ville,
            'participants' => $sortie->getParticipants(),
            'date'=>new \DateTime(),
            'isParticipant'=>$sortie->getParticipants()->contains($user)

        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function deleteSortie(
        int $id,
        Sortie $sortie,
        EntityManagerInterface $entityManager
    )
    {

        $entityManager->remove($sortie);
        $entityManager->flush();

        $user = $this->getUser()->getId();
        $this->addFlash('danger', 'Votre sortie a bien été suprimmée');
        return $this->redirectToRoute('main_home', [
            'id' => $user
        ]);

        return $this->redirectToRoute('main_home');
    }


}
