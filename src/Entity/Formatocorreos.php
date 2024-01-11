<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Becados
 *
 * @ORM\Table(name="formatocorreos")
 * @ORM\Entity
 */
class Formatocorreos {

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
   * @ORM\Column(name="formato", type="string", length=45, nullable=false)
   */
  private $formato;

  /**
   * @var string
   *
   * @ORM\Column(name="head", type="string", length=20000, nullable=true)
   */
  private $head;

  /**
   * @var string
   *
   * @ORM\Column(name="bodybefore", type="string", length=10000, nullable=true)
   */
  private $bodybefore;

  /**
   * @var string|null
   *
   * @ORM\Column(name="bodyafter", type="string", length=10000, nullable=true)
   */
  private $bodyafter;

  function getId() {
    return $this->id;
  }

  function getFormato() {
    return $this->formato;
  }

  function getHead() {
    return $this->head;
  }

  function getBodybefore() {
    return $this->bodybefore;
  }

  function getBodyafter() {
    return $this->bodyafter;
  }

  function setId($id) {
    $this->id = $id;
  }

  function setFormato($formato) {
    $this->formato = $formato;
  }

  function setHead($head) {
    $this->head = $head;
  }

  function setBodybefore($bodybefore) {
    $this->bodybefore = $bodybefore;
  }

  function setBodyafter($bodyafter) {
    $this->bodyafter = $bodyafter;
  }  
}
