<?php

namespace App\Entity;

use App\Repository\DashModulesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DashModulesRepository::class)
 */
class DashModules
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Modules", inversedBy="barra")
     */
    private $modulo;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private $bar;

    /**
     * @return mixed
     */
    public function getBar()
    {
        return $this->bar;
    }

    /**
     * @return mixed
     */
    public function getModulo()
    {
        return $this->modulo;
    }

    /**
     * @param mixed $modulo
     */
    public function setModulo($modulo): void
    {
        $this->modulo = $modulo;
    }



    /**
     * @param mixed $bar
     */
    public function setBar($bar): void
    {
        $this->bar = $bar;
    }

    /**
     * @return mixed
     */

    public function getInstalledModules()
    {
        return $this->installedModules;
    }

    /**
     * @param mixed $installedModules
     */
    public function setInstalledModules($installedModules): void
    {
        $this->installedModules = $installedModules;
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}
