<?php

namespace App\Entity;

class Sede
{
    private $id;

    private $nucleo;

    private $nombreinstitucion;

    function getId() {
      return $this->id;
    }

    function getNucleo() {
      return $this->nucleo;
    }

    function getNombreinstitucion() {
      return $this->nombreinstitucion;
    }

    function setId($id) {
      $this->id = $id;
    }

    function setNucleo($nucleo) {
      $this->nucleo = $nucleo;
    }

    function setNombreinstitucion($nombreinstitucion) {
      $this->nombreinstitucion = $nombreinstitucion;
    }


}
