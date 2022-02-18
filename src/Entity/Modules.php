<?php

namespace App\Entity;

use App\Repository\ModulesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ModulesRepository::class)
 */
class Modules
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Nombre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $funcion;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\DashModules", mappedBy="modulo")
     */
    private $barra;

    /**
     * @return mixed
     */
    public function getBarra()
    {
        return $this->barra;
    }

    /**
     * @param mixed $barra
     */
    public function setBarra($barra): void
    {
        $this->barra = $barra;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->Nombre;
    }

    public function setNombre(string $Nombre): self
    {
        $this->Nombre = $Nombre;

        return $this;
    }

    public function getFuncion(): ?string
    {
        return $this->funcion;
    }

    public function setFuncion(string $funcion): self
    {
        $this->funcion = $funcion;

        return $this;
    }
}
