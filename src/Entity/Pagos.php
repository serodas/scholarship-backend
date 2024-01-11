<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pagos
 *
 * @ORM\Table(name="pagos", indexes={@ORM\Index(name="fk_pagos_universidades1_idx", columns={"iduniversidad"}), @ORM\Index(name="fk_pagos_becados1_idx", columns={"idbecado"})})
 * @ORM\Entity
 */
class Pagos
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
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="date", nullable=false)
     */
    private $fecha;

    /**
     * @var int|null
     *
     * @ORM\Column(name="valor", type="integer", nullable=true)
     */
    private $valor;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nombrecarrera", type="string", length=45, nullable=true)
     */
    private $nombrecarrera;

    /**
     * @var string|null
     *
     * @ORM\Column(name="semestre", type="string", length=45, nullable=true)
     */
    private $semestre;

    /**
     * @var \Becado
     *
     * @ORM\ManyToOne(targetEntity="Becado")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idbecado", referencedColumnName="id")
     * })
     */
    private $idbecado;

    /**
     * @var \Universidades
     *
     * @ORM\ManyToOne(targetEntity="Universidades")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="iduniversidad", referencedColumnName="id")
     * })
     */
    private $iduniversidad;

    public function getId(): int
    {
        return $this->id;
    }

    public function getFecha(): \DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): self
    {
        $this->fecha = $fecha;

        return $this;
    }

    public function getValor(): int
    {
        return $this->valor;
    }

    public function setValor(int $valor): self
    {
        $this->valor = $valor;

        return $this;
    }

    public function getNombrecarrera(): string
    {
        return $this->nombrecarrera;
    }

    public function setNombrecarrera(string $nombrecarrera): self
    {
        $this->nombrecarrera = $nombrecarrera;

        return $this;
    }

    public function getSemestre(): string
    {
        return $this->semestre;
    }

    public function setSemestre(string $semestre): self
    {
        $this->semestre = $semestre;

        return $this;
    }

    public function getIdbecado(): Becado
    {
        return $this->idbecado;
    }

    public function setIdbecado(Becado $idbecado): self
    {
        $this->idbecado = $idbecado;

        return $this;
    }

    public function getIduniversidad(): Universidades
    {
        return $this->iduniversidad;
    }

    public function setIduniversidad(Universidades $iduniversidad): self
    {
        $this->iduniversidad = $iduniversidad;

        return $this;
    }


}
