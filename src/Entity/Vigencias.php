<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Vigencias
 *
 * @ORM\Table(name="vigencias")
 * @ORM\Entity
 */
class Vigencias
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
     * @var int
     *
     * @ORM\Column(name="vigencia", type="integer", nullable=false)
     */
    private $vigencia;

    /**
     * @var int
     *
     * @ORM\Column(name="valorbeca", type="integer", nullable=false)
     */
    private $valorbeca;

    public function getId(): int
    {
        return $this->id;
    }

    public function getVigencia(): int
    {
        return $this->vigencia;
    }

    public function setVigencia(int $vigencia): self
    {
        $this->vigencia = $vigencia;

        return $this;
    }

    public function getValorbeca(): int
    {
        return $this->valorbeca;
    }

    public function setValorbeca(int $valorbeca): self
    {
        $this->valorbeca = $valorbeca;

        return $this;
    }


}
