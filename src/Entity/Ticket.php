<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use App\Validator\Constraints as MyAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TicketRepository")
 */
/*déclaration de la class de nom Ticket*/
class Ticket
{
    /*déclaration des attributs de la class, ici en private donc inaccessible à l'exterieur dee cette lcass 
    declaration des anotations symfony */

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $type;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=50)
     *      @Assert\Length(
     *      min = 4,
     *      max = 50,
     *      minMessage = "La taille minimale est de 4 caractères.",
     *      maxMessage = "La taille maximale est de 50 caractères."
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=50)
     *      @Assert\Length(
     *      min = 4,
     *      max = 50,
     *      minMessage = "La taille minimzle est de 4 caractères.",
     *      maxMessage = "La taille maximale est de 50 caractères."
     * )
     */
    private $firstName;
    
    /**
     * @Assert\NotBlank
     * @MyAssert\ConstraintBirthDate
     * @ORM\Column(type="string")
     */
    private $birthDate;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $country;

    /**
     * @Assert\NotBlank
     * @MyAssert\ConstraintDateBooking
     * @ORM\Column(type="string")
     */
    private $dateBooking;

    /**
     * @ORM\Column(type="string", length=255)
     *     @Assert\Email(
     *     message = "L'E-mail n'est pas valide.",
     *     checkMX = true
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="integer")
     */
    private $sendTicket;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="string")
     */
    private $code;

    /* declaration du constructeur, fonction lancé automatiquement à l appel de cette class */
    public function __construct()
    {
        /* attribution des valeurs au variable */
        $this->sendTicket = 0;
        $this->date = new \DateTime();
        $this->code = openssl_random_pseudo_bytes(16);

        //Convert the binary data into hexadecimal representation.
        $this->code = bin2hex($this->code);
    }


    /* declaration des getter ( recupérer ) et des setter ( attribuer ) 
    ici getId() qui nous permet de récupérer la valeur de l attribut $id du dessus*/
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    /* ici on declare un setter setType( et qui contient un parametre de type entier et qui est appelé $type), 
    a quoi sert le self ??????????  
    pourquoi utilise t on self pour les setter et pourquoi la syntaxe du getter contient un ? avant le type ??????*/
    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getBirthDate(): ?string
    {
        return $this->birthDate;
    }

    public function setBirthDate(string $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getDateBooking(): ?string
    {
        return $this->dateBooking;
    }

    public function setDateBooking(string $dateBooking): self
    {
        $this->dateBooking = $dateBooking;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getSendTicket(): ?int
    {
        return $this->sendTicket;
    }

    public function setSendTicket(int $sendTicket): self
    {
        $this->sendTicket = $sendTicket;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }
}
