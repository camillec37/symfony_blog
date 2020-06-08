<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     *
     * @Assert\NotBlank(message="Le nom est obligatoire")
     * @Assert\Length(max="50",
     *     maxMessage="Le nom ne doit pas faire plus de {{ limit }} caractères")
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=20)
     *
     * @Assert\NotBlank(message="Le prénom est obligatoire")
     * @Assert\Length(max="20",
     *     maxMessage="Le prénom ne doit pas faire plus de {{ limit }} caractères")
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     *
     * @Assert\NotBlank(message="L'email est obligatoire")
     * @Assert\Email(message="L'email n'est pas valide")
     * @Assert\Length(max="100",
     *     maxMessage="L'email ne doit pas faire plus de {{ limit }} caractères")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=20)
     */
    //par défaut role_user
    private $role = 'ROLE_USER';

    /**
     * Le mot de passe en clair pour interragir avec le formulaire d'inscription
     * pas d'ORM car il n'est pas relié à la bdd, seulement celui qui sera crypté
     * @var string|null
     *
     * @Assert\NotBlank(message="Le mot de passe est obligatoire")
     * @Assert\Regex("/^[a-zA-Z0-9_]{6,20}$/", message="Mot de passe non conforme")
     *
     */
    private $plainPassword;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * @param string|null $plainPassword
     * @return User
     */
    public function setPlainPassword(?string $plainPassword): User
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }


    public function getRoles()
    {
       return [$this->role];
    }

    public function getSalt()
    {
        // méthode qui rajoute une concaténation pout crypter le mot de passe
        //inutile car l'algo de cryptage utilisé contient deja son grain de sel
    }

    public function getUsername()
    {
        //l'authentifiant est l'email
        return $this->email;
    }

    public function eraseCredentials()
    {
        // n'est utile que lorsqu'il y a des données sensibles en bdd
    }
}
