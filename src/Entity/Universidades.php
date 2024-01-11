<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Universidades
 *
 * @ORM\Table(name="universidades")
 * @ORM\Entity
 */
class Universidades
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=80, nullable=false)
     */
    private $nombre;

    /**
     * @var string|null
     *
     * @ORM\Column(name="sede", type="string", length=45, nullable=true)
     */
    private $sede;

    /**
     * @var string|null
     *
     * @ORM\Column(name="direccion", type="string", length=150, nullable=true)
     */
    private $direccion;

    /**
     * @var string|null
     *
     * @ORM\Column(name="telefono", type="string", length=45, nullable=true)
     */
    private $telefono;

    /**
     * @var string|null
     *
     * @ORM\Column(name="correoelectronico", type="string", length=80, nullable=true)
     */
    private $correoelectronico;

    public function getId(): int
    {
        return $this->id;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getSede(): string
    {
        return $this->sede;
    }

    public function setSede(string $sede): self
    {
        $this->sede = $sede;

        return $this;
    }

    public function getDireccion(): string
    {
        return $this->direccion;
    }

    public function setDireccion(string $direccion): self
    {
        $this->direccion = $direccion;

        return $this;
    }

    public function getTelefono(): string
    {
        return $this->telefono;
    }

    public function setTelefono(string $telefono): self
    {
        $this->telefono = $telefono;

        return $this;
    }

    public function getCorreoelectronico(): string
    {
        return $this->correoelectronico;
    }

    public function setCorreoelectronico(string $correoelectronico): self
    {
        $this->correoelectronico = $correoelectronico;

        return $this;
    }


}
