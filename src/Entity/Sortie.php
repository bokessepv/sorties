<?php

namespace App\Entity;

use App\Repository\SortieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=SortieRepository::class)
 */
class Sortie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="Veuillez inserer votre nom de sortie !")
     * @Assert\Length(min=2, max=50)
     * @ORM\Column(type="string", length=50)
     */
    private $nom;

    /**
     * @Assert\GreaterThanOrEqual(propertyPath="dateLimiteInscription")
     * @ORM\Column(type="datetime")
     */
    private $dateHeureDebut;

    /**
     * @Assert\Range(min="1", max="1000")
     * @ORM\Column(type="integer")
     */
    private $duree;

    /**
     * @Assert\LessThanOrEqual(propertyPath="dateHeureDebut")
     * @ORM\Column(type="date")
     */
    private $dateLimiteInscription;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbInscriptionMax;

    /**
     * @ORM\ManyToOne(targetEntity=Etat::class, inversedBy="sortie")
     * @ORM\JoinColumn(nullable=false)
     */
    private $etat;

    /**
     * @ORM\Column(type="text")
     */
    private $infosSortie;



    /**
     * @ORM\ManyToOne(targetEntity=Lieu::class, inversedBy="lieu")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank()
     */
    private $lieu;



    /**
     * @ORM\ManyToMany(targetEntity=Participant::class, mappedBy="participants")
     */
    private $participants;

    /**
     * @ORM\ManyToOne(targetEntity=Participant::class, inversedBy="organisateur")
     * @ORM\JoinColumn(nullable=false)
     */
    private $organisateur;

    /**
     * @ORM\ManyToOne(targetEntity=Campus::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $campusOrganisateur;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $photo;

    /**
     *
     */
    public function __construct()
    {
        $this->participants = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getNom(): ?string
    {
        return $this->nom;
    }

    /**
     * @param string $nom
     * @return $this
     */
    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getDateHeureDebut(): ?\DateTimeInterface
    {
        return $this->dateHeureDebut;
    }

    /**
     * @param \DateTimeInterface $dateHeureDebut
     * @return $this
     */
    public function setDateHeureDebut(\DateTimeInterface $dateHeureDebut): self
    {
        $this->dateHeureDebut = $dateHeureDebut;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getDuree(): ?int
    {
        return $this->duree;
    }

    /**
     * @param int $duree
     * @return $this
     */
    public function setDuree(int $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getDateLimiteInscription(): ?\DateTimeInterface
    {
        return $this->dateLimiteInscription;
    }

    /**
     * @param \DateTimeInterface $dateLimiteInscription
     * @return $this
     */
    public function setDateLimiteInscription(\DateTimeInterface $dateLimiteInscription): self
    {
        $this->dateLimiteInscription = $dateLimiteInscription;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getNbInscriptionMax(): ?int
    {
        return $this->nbInscriptionMax;
    }

    /**
     * @param int $nbInscriptionMax
     * @return $this
     */
    public function setNbInscriptionMax(int $nbInscriptionMax): self
    {
        $this->nbInscriptionMax = $nbInscriptionMax;

        return $this;
    }


    /**
     * @return mixed
     */
    public function __toString()
    {
        return $this->nom;
    }

    /**
     * @return Etat|null
     */
    public function getEtat(): ?Etat
    {
        return $this->etat;
    }

    /**
     * @param Etat|null $etat
     * @return $this
     */
    public function setEtat(?Etat $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getInfosSortie(): ?string
    {
        return $this->infosSortie;
    }

    /**
     * @param string $infosSortie
     * @return $this
     */
    public function setInfosSortie(string $infosSortie): self
    {
        $this->infosSortie = $infosSortie;

        return $this;
    }


    /**
     * @return Lieu|null
     */
    public function getLieu(): ?Lieu
    {
        return $this->lieu;
    }

    /**
     * @param Lieu|null $lieu
     * @return $this
     */
    public function setLieu(?Lieu $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }


    /**
     * @return Collection<int, Participant>
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    /**
     * @param Participant $participant
     * @return $this
     */
    public function addParticipant(Participant $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants[] = $participant;
            $participant->addParticipant($this);
        }

        return $this;
    }

    /**
     * @param Participant $participant
     * @return $this
     */
    public function removeParticipant(Participant $participant): self
    {
        if ($this->participants->removeElement($participant)) {
            $participant->removeParticipant($this);
        }

        return $this;
    }

    /**
     * @return Participant|null
     */
    public function getOrganisateur(): ?Participant
    {
        return $this->organisateur;
    }

    /**
     * @param Participant|null $organisateur
     * @return $this
     */
    public function setOrganisateur(?Participant $organisateur): self
    {
        $this->organisateur = $organisateur;

        return $this;
    }

    /**
     * @return Campus|null
     */
    public function getCampusOrganisateur(): ?Campus
    {
        return $this->campusOrganisateur;
    }

    /**
     * @param Campus|null $campusOrganisateur
     * @return $this
     */
    public function setCampusOrganisateur(?Campus $campusOrganisateur): self
    {
        $this->campusOrganisateur = $campusOrganisateur;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    /**
     * @param string|null $photo
     * @return $this
     */
    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }
}
