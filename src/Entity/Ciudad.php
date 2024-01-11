<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Requerimientos
 *
 * @ORM\Table(name="ciudades")
 * @ORM\Entity
 */
class Ciudad
{
    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(name="codigo", type="string", length=5, nullable=false)
     */
    private $codigo;
    
    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=45, nullable=false)
     */
    private $nombre;
    
    /**
     * @var string
     *
     * @ORM\Column(name="codigodepartamento", type="string", length=2, nullable=false)
     */
    private $codigodepartamento;

    function getCodigo() {
      return $this->codigo;
    }

    function getNombre() {
      return $this->nombre;
    }

    function getCodigodepartamento() {
      return $this->codigodepartamento;
    }

    function setCodigo($codigo) {
      $this->codigo = $codigo;
    }

    function setNombre($nombre) {
      $this->nombre = $nombre;
    }

    function setCodigodepartamento($codigodepartamento) {
      $this->codigodepartamento = $codigodepartamento;
    }
}
