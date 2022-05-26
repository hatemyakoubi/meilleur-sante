<?php

namespace App\Entity;

use App\Repository\PatientRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PatientRepository::class)
 */
class Patient
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $identificaion;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nomComplet;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $sexe;

    /**
     * @ORM\Column(type="integer")
     */
    private $age;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $localisation;

    /**
     * @ORM\Column(type="float")
     */
    private $temperature;

    /**
     * @ORM\Column(type="float")
     */
    private $pouls;

    /**
     * @ORM\Column(type="float")
     */
    private $oxygene;

    /**
     * @ORM\Column(type="boolean")
     */
    private $target;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdentificaion(): ?int
    {
        return $this->identificaion;
    }

    public function setIdentificaion(int $identificaion): self
    {
        $this->identificaion = $identificaion;

        return $this;
    }

    public function getNomComplet(): ?string
    {
        return $this->nomComplet;
    }

    public function setNomComplet(string $nomComplet): self
    {
        $this->nomComplet = $nomComplet;

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(string $sexe): self
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    public function setLocalisation(string $localisation): self
    {
        $this->localisation = $localisation;

        return $this;
    }

    public function getTemperature(): ?float
    {
        return $this->temperature;
    }

    public function setTemperature(float $temperature): self
    {
        $this->temperature = $temperature;

        return $this;
    }

    public function getPouls(): ?float
    {
        return $this->pouls;
    }

    public function setPouls(float $pouls): self
    {
        $this->pouls = $pouls;

        return $this;
    }

    public function getOxygene(): ?float
    {
        return $this->oxygene;
    }

    public function setOxygene(float $oxygene): self
    {
        $this->oxygene = $oxygene;

        return $this;
    }

    public function getTarget(): ?bool
    {
        return $this->target;
    }

    public function setTarget(bool $target): self
    {
        $this->target = $target;

        return $this;
    }
}
