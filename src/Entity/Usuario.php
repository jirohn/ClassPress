<?php

namespace App\Entity;

use App\Repository\UsuarioRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UsuarioRepository::class)
 */
class Usuario implements UserInterface, PasswordAuthenticatedUserInterface
{
    const REGISTRO_EXITOSO = "El registro ha sido satisfactorio";
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
    * @ORM\Column(type="boolean")    
    */
    private $baneado;

    /**
    * @ORM\Column(type="string")    
    */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comentarios", mappedBy="usuario")
     */
    private $comentarios;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Anuncios", mappedBy="usuario")
     */
    private $anuncios;
    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    public function __construct()
    {
        $this->baneado=false;
        $this->roles=['ROLE_USER'];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getAnuncios()
    {
        return $this->anuncios;
    }

    /**
     * @param mixed $anuncios
     */
    public function setAnuncios($anuncios): void
    {
        $this->anuncios = $anuncios;
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


    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return string the hashed password for this user
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }/**
 * @return mixed
 */
public function getBaneado()
{
    return $this->baneado;
}/**
 * @param mixed $baneado
 */
public function setBaneado($baneado): void
{
    $this->baneado = $baneado;
}/**
 * @return mixed
 */
public function getNombre()
{
    return $this->nombre;
}/**
 * @param mixed $nombre
 */
public function setNombre($nombre): void
{
    $this->nombre = $nombre;
}

    /**
     * @return mixed
     */
    public function getComentarios()
    {
        return $this->comentarios;
    }

    /**
     * @param mixed $comentarios
     */
    public function setComentarios($comentarios): void
    {
        $this->comentarios = $comentarios;
    }

    
}
