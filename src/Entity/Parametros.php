<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Parametros
 *
 * @ORM\Table(name="parametros")
 * @ORM\Entity
 */
class Parametros {

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
   * @ORM\Column(name="fechanotificacion", type="string", length=50, nullable=true)
   */
  private $fechanotificacion;

  /**
   * @var string
   *
   * @ORM\Column(name="textonotificacion", type="string", length=5000, nullable=false)
   */
  private $textonotificacion;

  public function getId(): int {
    return $this->id;
  }

  public function getFechanotificacion(): string {
    return $this->fechanotificacion;
  }

  public function setFechanotificacion(string $fechanotificacion): self {
    $this->fechanotificacion = $fechanotificacion;

    return $this;
  }

  public function getTextonotificacion(): string {
    return $this->textonotificacion;
  }

  public function setTextonotificacion(string $textonotificacion): self {
    $this->textonotificacion = $textonotificacion;

    return $this;
  }
}
