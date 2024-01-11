<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Instituciones
 *
 * @ORM\Table(name="instituciones")
 * @ORM\Entity
 */
class Institucion
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
     * @var int|null
     *
     * @ORM\Column(name="nucleo", type="integer", nullable=true)
     */
    private $nucleo;

    /**
     * @var string
     *
     * @ORM\Column(name="jerarquia", type="string", length=4, nullable=false)
     */
    private $jerarquia;

    /**
     * @var string
     *
     * @ORM\Column(name="publico", type="string", length=1, nullable=false)
     */
    private $publico;

    
    /**
     * @var string
     *
     * @ORM\Column(name="nombreinstitucion", type="string", length=120, nullable=false)
     */
    private $nombreinstitucion;

    /**
     * @var string|null
     *
     * @ORM\Column(name="comuna", type="string", length=45, nullable=true)
     */
    private $comuna;

    /**
     * @var string
     *
     * @ORM\Column(name="direccion", type="string", length=120, nullable=false)
     */
    private $direccion;
    
    /**
     * @var \Ciudad
     *
     * @ORM\ManyToOne(targetEntity="Ciudad")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="codigociudad", referencedColumnName="codigo")
     * })
     */
    private $ciudad;

    /**
     * @var string
     *
     * @ORM\Column(name="telfijo", type="string", length=45, nullable=false)
     */
    private $telfijo;

    /**
     * @var string|null
     *
     * @ORM\Column(name="movil", type="string", length=45, nullable=true)
     */
    private $movil;

    /**
     * @var string|null
     *
     * @ORM\Column(name="cargo", type="string", length=45, nullable=true)
     */
    private $cargo;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nombreresponsable", type="string", length=80, nullable=true)
     */
    private $nombreresponsable;

    /**
     * @var string
     *
     * @ORM\Column(name="correoelectronico", type="string", length=80, nullable=false)
     */
    private $correoelectronico;

    /**
     * @var string|null
     *
     * @ORM\Column(name="zona", type="string", length=45, nullable=true)
     */
    private $zona;

    /**
     * @var string|null
     *
     * @ORM\Column(name="codigodane", type="string", length=45, nullable=true)
     */
    private $codigodane;

    /**
     * @var string|null
     *
     * @ORM\Column(name="password", type="string", length=80, nullable=true)
     */
    private $password;

    /**
     * @var int|null
     *
     * @ORM\Column(name="idprincipal", type="integer", nullable=true)
     */
    private $idprincipal;
    
    /**
     * @var int|null
     *
     * @ORM\Column(name="fechanotificacion", type="integer", nullable=true)
     */
    private $fechanotificacion;
    
    private $sedes;

    /**
     * @var int|null
     *
     * @ORM\Column(name="ultimoingreso", type="integer", nullable=true)
     */
    private $ultimoingreso;

    public function getId(): int
    {
        return $this->id;
    }

    public function getNucleo(): int
    {
        return $this->nucleo;
    }

    public function setNucleo(int $nucleo): self
    {
        $this->nucleo = $nucleo;

        return $this;
    }

    public function getJerarquia(): string
    {
        return $this->jerarquia;
    }

    public function setJerarquia(string $jerarquia): self
    {
        $this->jerarquia = $jerarquia;

        return $this;
    }

    function getPublico() {
      return $this->publico;
    }

    function setPublico($publico) {
      $this->publico = $publico;
    }
    
    public function getNombreinstitucion(): string
    {
        return $this->nombreinstitucion;
    }

    public function setNombreinstitucion(string $nombreinstitucion): self
    {
        $this->nombreinstitucion = $nombreinstitucion;

        return $this;
    }

    public function getComuna(): string
    {
        return $this->comuna;
    }

    public function setComuna(string $comuna): self
    {
        $this->comuna = $comuna;

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

    public function getTelfijo()
    {
        return $this->telfijo;
    }

    public function setTelfijo(string $telfijo): self
    {
        $this->telfijo = $telfijo;

        return $this;
    }

    public function getMovil()
    {
        return $this->movil;
    }

    public function setMovil(string $movil): self
    {
        $this->movil = $movil;

        return $this;
    }

    public function getCargo()
    {
        return $this->cargo;
    }

    public function setCargo(string $cargo): self
    {
        $this->cargo = $cargo;

        return $this;
    }

    public function getNombreresponsable()
    {
        return $this->nombreresponsable;
    }

    public function setNombreresponsable(string $nombreresponsable): self
    {
        $this->nombreresponsable = $nombreresponsable;

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

    public function getZona()
    {
        return $this->zona;
    }

    public function setZona(string $zona): self
    {
        $this->zona = $zona;

        return $this;
    }

    public function getCodigodane()
    {
        return $this->codigodane;
    }

    public function setCodigodane(string $codigodane): self
    {
        $this->codigodane = $codigodane;

        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword(string $password)
    {
        $this->password = $password;

        return $this;
    }

    public function getIdprincipal()
    {
        return $this->idprincipal;
    }

    public function setIdprincipal(int $idprincipal): self
    {
        $this->idprincipal = $idprincipal;

        return $this;
    }
    
    function getFechanotificacion() {
      return $this->fechanotificacion;
    }

    function setFechanotificacion($fechanotificacion) {
      $this->fechanotificacion = $fechanotificacion;
    }

    function getSedes() {
      return $this->sedes;
    }

    function setSedes($sedes) {
      $this->sedes = $sedes;
    }
    
    function getCiudad() {
      return $this->ciudad;
    }

    function setCiudad(Ciudad $ciudad) {
      $this->ciudad = $ciudad;
    }

    function getUltimoingreso() {
        return $this->ultimoingreso;
      }
    
      function setUltimoingreso($ultimoingreso) {
        $this->ultimoingreso = $ultimoingreso;
      }
    
}
