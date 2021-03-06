<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Form\LieuType;
use App\Form\SortieType;
use App\Repository\EtatRepository;
use App\Repository\LieuRepository;
use App\Repository\SortieRepository;
use App\Repository\VilleRepository;
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
        SluggerInterface $slugger,
        SortieRepository $sortieRepository
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



            $sortie->addParticipant($participant);
            $sortie->setCampusOrganisateur($campusOrganisateur);
            $sortie->setOrganisateur($this->getUser());

            // J'initialise deux var $publish et $save par rapport a l'etat de la sortie

            $publish = $request->request->get('publish');
            $save = $request->request->get('save');

            /* Ici je fais en sorte que si la var save n'est pas null, alors tu me cree la sortie et tu setera l'etat a 1 qui dans la bdd est cree
                sinon si l'utlisateur publie sa sortie, tu mets l'etat de sortie a 2 qui est ouvert
            */
            if($save != null)
            {
                $user = $this->getUser()->getId();
                $etatCreation = $etatRepository->find(1);
                $sortie->setEtat($etatCreation);
                $entityManager->persist($sortie);
                $entityManager->flush();
                $this->addFlash('success', 'Votre sortie ?? bien ??t?? sauvegard??e');
                return $this->redirectToRoute('main_home', [
                    'id' => $user
                ]);

            }else
            {
                $sorties = $sortieRepository->findAll();
                $etatOuvert = $etatRepository->find(2);
                $sortie->setEtat($etatOuvert);
                $entityManager->persist($sortie);
                $entityManager->flush();
                $this->addFlash('success', 'Votre sortie ?? bien ??t?? publi??e');
                return $this->redirectToRoute('main_home', [
                    'sorties' => $sorties
                ]);
            }


        }

        if($lieuForm->isSubmitted() && $lieuForm->isValid()){
            $entityManager->persist($lieu);
            $entityManager->flush();

            $this->addFlash('success', 'Lieu ajout??s');
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

            $this->addFlash('Bravo', 'Sortie modifi??s !');
            return $this->redirectToRoute('sortie_details', ['id'=> $sortie->getId()]);
        }

        if($lieuForm->isSubmitted() && $lieuForm->isValid()){
            $entityManager->persist($lieu);
            $entityManager->flush();

            $this->addFlash('Bravo', 'Lieu ajout??s');
            return $this->redirectToRoute('sortie_create');

        }



        return $this->render('sortie/updateSortie.html.twig', [
            'sortieForm' => $sortieForm->createView(),
            'lieuForm' => $lieuForm->createView(),
            'sortie' => $sortie,
            'campus' => $this->getUser()->getCampus()
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
     * @Route("/home/delete/{id}", name="delete_sortie")
     */
    public function deleteSortie(
        Sortie $sortie,
        EntityManagerInterface $entityManager
    )
    {

        $entityManager->remove($sortie);
        $entityManager->flush();

        $user = $this->getUser()->getId();
        $this->addFlash('danger', 'Votre sortie a bien ??t?? suprimm??e');
        return $this->redirectToRoute('main_home', [
            'id' => $user
        ]);
    }

    // Inscription sortie
    /**
     * @Route("/sortie/inscription{id}", name="inscription_sortie")
     */
    public function inscriptionSortie(int $id, SortieRepository $sortieRepository, EntityManagerInterface $entityManager)
    {
        $sortie = $sortieRepository->find($id);
        $participant = $this->getUser();

        $sortie->addParticipant($participant);
        $entityManager->persist($sortie);
        $entityManager->flush();


        $this->addFlash('success', 'Inscription r??ussite');
        return $this->redirectToRoute('sortie_details',[
            'id'=>$sortie->getId()
        ]);
    }


    // Desinscription sortie
    /**
     * @Route("/sortie/seDesinscrire{id}", name="desinscrire_sortie")
     */
    public function sortieDesister(int $id, SortieRepository $sortieRepository, EntityManagerInterface $entityManager)
    {
        $sortie = $sortieRepository->find($id);
        $participant = $this->getUser();

        $sortie->removeParticipant($participant);
        $entityManager->persist($sortie);
        $entityManager->flush();

        $this->addFlash('danger', 'Votre d??sinscription a  ??t?? pris en compte');
        return $this->redirectToRoute('sortie_details',[
            'id' =>$sortie->getId(),
        ]);
    }

    // Annuler sortie
    /**
     * @Route("/sortie/cancel{id}", name="cancel_sortie")
     */
    public function cancelSortie(int $id,SortieRepository $sortieRepository,
                                     Request $request,
                                     EntityManagerInterface $entityManager,
                                     EtatRepository $etatRepository)
    {
        $sortie = $sortieRepository->find($id);
        $annuler = $request->request->get('annuler');

        if ($annuler != null)
        {
            $etatAnnuler = $etatRepository->find(6);
            $sortie->setEtat($etatAnnuler);
            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash('danger', 'Votre sortie bien ??t?? annul??e');
            return $this->redirectToRoute('main_home');
        }



        return $this->render('sortie/cancel.html.twig', [
            'sortie' =>$sortie,
            'campus' => $this->getUser()->getCampus(),
            'lieu' => $sortie->getLieu(),
            'ville' => $sortie->getLieu()->getVille()
        ]);
    }


}
