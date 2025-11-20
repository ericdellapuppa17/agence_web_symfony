<?php
// src/Entity/Ticket.php
namespace App\Entity;

use App\Entity\Categorie;
use App\Repository\TicketRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TicketRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Ticket
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // auteur = email du client
    #[ORM\Column(length: 180)]
    #[Assert\NotBlank(message: "L'email est obligatoire !" )]
    #[Assert\Email(message: "Veuillez saisir un email valide !" )]
    private ?string $auteur = null;

    // date d'ouverture du ticket générée automatiquement
    #[ORM\Column]
    private ?\DateTimeImmutable $dateOuverture = null;

    // date de clôture du ticket
    #[ORM\Column(nullable: true)]
    private ?\DateTime $dateCloture = null;

    // decription du problème
    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: "La description du problème est obligatoire !")]
    #[Assert\Length(
        min: 20,
        max: 250,
        minMessage: "La description doit contenir au moins {{ limit }} caractères",
        maxMessage: "La description doit contenir au maximum {{ limit }} caractères",
    )]
    private ?string $description = null;

    // catégorie ( liste évolutive liée à la table categorie)
    #[ORM\ManyToOne(inversedBy: 'tickets')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: "Veuillez sélectionner une catégorie !")]
    private ?Categorie $categorie = null;

    // statut ( avec valeur par défaut 'Ouvert' à la création )
    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: "Un statut est requis !")]
    #[Assert\Length(max: 50, maxMessage: "Le statut be peut dépasser {{ limit }} caractères !")]
    private ?string $statut = null;

    // responsable ( à assigner )
    #[ORM\Column(length: 150, nullable: true)]
    #[Assert\Length(max: 250, maxMessage: "Le nom du responsable ne peut dépasser {{ limit }} caractères !")]
    private ?string $responsable = null;

    public function __construct()
    {
        // Initialisation à la création du ticket
        $this->dateOuverture = new \DateTimeImmutable();
        $this->statut = 'Nouveau';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuteur(): ?string
    {
        return $this->auteur;
    }

    public function setAuteur(string $auteur): static
    {
        $this->auteur = $auteur;

        return $this;
    }

    public function getDateOuverture(): ?\DateTimeImmutable
    {
        return $this->dateOuverture;
    }

    public function setDateOuverture(\DateTimeImmutable $dateOuverture): static
    {
        $this->dateOuverture = $dateOuverture;

        return $this;
    }

    public function getDateCloture(): ?\DateTime
    {
        return $this->dateCloture;
    }

    public function setDateCloture(?\DateTime $dateCloture): static
    {
        $this->dateCloture = $dateCloture;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getResponsable(): ?string
    {
        return $this->responsable;
    }

    public function setResponsable(?string $responsable): static
    {
        $this->responsable = $responsable;

        return $this;
    }
}
