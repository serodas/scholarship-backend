<?php

namespace App\Entity;

use App\Repository\BecadoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Becados
 *
 * @ORM\Table(name="becados", uniqueConstraints={@ORM\UniqueConstraint(name="uk_becado_doc", columns={"tipodocumento", "numerodocumento"})}, indexes={@ORM\Index(name="fk_becado_institucion_idx", columns={"idinstitucion"})})
 * @ORM\Entity(repositoryClass=BecadoRepository::class)
 */
class Becado
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
     * @ORM\Column(name="tipodocumento", type="string", length=2, nullable=false)
     */
    private $tipodocumento;

    /**
     * @var string
     *
     * @ORM\Column(name="numerodocumento", type="string", length=20, nullable=false)
     */
    private $numerodocumento;

    /**
     * @var string
     *
     * @ORM\Column(name="primerapellido", type="string", length=20, nullable=false)
     */
    private $primerapellido;

    /**
     * @var string|null
     *
     * @ORM\Column(name="segundoapellido", type="string", length=20, nullable=true)
     */
    private $segundoapellido;

    /**
     * @var string
     *
     * @ORM\Column(name="primernombre", type="string", length=20, nullable=false)
     */
    private $primernombre;

    /**
     * @var string|null
     *
     * @ORM\Column(name="segundonombre", type="string", length=20, nullable=true)
     */
    private $segundonombre;

    /**
     * @var string|null
     *
     * @ORM\Column(name="correoelectronico", type="string", length=50, nullable=true)
     */
    private $correoelectronico;

    /**
     * @var string|null
     *
     * @ORM\Column(name="telefonofijo", type="string", length=40, nullable=true)
     */
    private $telefonofijo;

    /**
     * @var string
     *
     * @ORM\Column(name="telefonomovil", type="string", length=10, nullable=false)
     */
    private $telefonomovil;

    /**
     * @var string
     *
     * @ORM\Column(name="codigociudad", type="string", length=6, nullable=false)
     */
    private $codigociudad;

    /**
     * @var string
     *
     * @ORM\Column(name="direccion", type="string", length=80, nullable=false)
     */
    private $direccion;

    /**
     * @var int
     *
     * @ORM\Column(name="valorbeca", type="integer", nullable=false)
     */
    private $valorbeca;

    /**
     * @var int|null
     *
     * @ORM\Column(name="vigenciainicia", type="integer", nullable=true)
     */
    private $vigenciainicia;

    /**
     * @var string|null
     *
     * @ORM\Column(name="jornada", type="string", length=45, nullable=true)
     */
    private $jornada;
    
    /**
     * @var string
     *
     * @ORM\Column(name="genero", type="string", length=1, nullable=false)
     */
    private $genero;

    /**
     * @var int
     *
     * @ORM\Column(name="vigencia", type="integer", nullable=false)
     */
    private $vigencia;

    /**
     * @var \Institucion
     *
     * @ORM\ManyToOne(targetEntity="Institucion", cascade={})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idinstitucion", referencedColumnName="id")
     * })
     */
    private $institucion;
    
    /**
     * @var string|null
     *
     * @ORM\Column(name="sede", type="string", length=100, nullable=true)
     */
    private $sede;
    
    /**
     * @var string
     *
     * @ORM\Column(name="mocodmotiv", type="string", length=2, nullable=false)
     */
    private $mocodmotiv;

    /**
     * @var string|null
     *
     * @ORM\Column(name="observacion", type="string", length=100, nullable=true)
     */
    private $observacion;
    
    /**
     * @var int|null
     *
     * @ORM\Column(name="consecutivo", type="integer", nullable=true)
     */
    private $consecutivo;
    
    /**
     * @var int|null
     *
     * @ORM\Column(name="fechaevaluacion", type="integer", nullable=true)
     */
    private $fechaevaluacion;
    
    /**
     * @var int|null
     *
     * @ORM\Column(name="envioinvitacion", type="integer", nullable=true)
     */
    private $envioinvitacion;
    
    /**
     * @var string
     *
     * @ORM\Column(name="idtipobeca", type="string", length=1, nullable=false)
     */
    private $idtipobeca;
    
  /**
   * @var datetime $fechapostulacion
   *
   * @ORM\Column(type="datetime")
   */
  private $fechapostulacion;

    /**
     * @var string
     *
     * @ORM\Column(name="esafiliado", type="string", length=1, nullable=true)
     */
    private $esafiliado;

    
    private $documentospresentados;
    

    public function getId()
    {
        return $this->id;
    }

    public function getTipodocumento()
    {
        return $this->tipodocumento;
    }

    public function setTipodocumento(string $tipodocumento)
    {
        $this->tipodocumento = $tipodocumento;

        return $this;
    }

    public function getNumerodocumento()
    {
        return $this->numerodocumento;
    }

    public function setNumerodocumento(string $numerodocumento)
    {
        $this->numerodocumento = $numerodocumento;

        return $this;
    }

    public function getPrimerapellido()
    {
        return $this->primerapellido;
    }

    public function setPrimerapellido(string $primerapellido)
    {
        $this->primerapellido = $primerapellido;

        return $this;
    }

    public function getSegundoapellido()
    {
        return $this->segundoapellido;
    }

    public function setSegundoapellido(string $segundoapellido)
    {
        $this->segundoapellido = $segundoapellido;

        return $this;
    }

    public function getPrimernombre()
    {
        return $this->primernombre;
    }

    public function setPrimernombre(string $primernombre)
    {
        $this->primernombre = $primernombre;

        return $this;
    }

    public function getSegundonombre()
    {
        return $this->segundonombre;
    }

    public function setSegundonombre(string $segundonombre)
    {
        $this->segundonombre = $segundonombre;

        return $this;
    }

    public function getCorreoelectronico()
    {
        return $this->correoelectronico;
    }

    public function setCorreoelectronico(string $correoelectronico)
    {
        $this->correoelectronico = $correoelectronico;

        return $this;
    }

    public function getTelefonofijo()
    {
        return $this->telefonofijo;
    }

    public function setTelefonofijo(string $telefonofijo)
    {
        $this->telefonofijo = $telefonofijo;

        return $this;
    }

    public function getTelefonomovil()
    {
        return $this->telefonomovil;
    }

    public function setTelefonomovil(string $telefonomovil)
    {
        $this->telefonomovil = $telefonomovil;

        return $this;
    }

    function getCodigociudad() {
      return $this->codigociudad;
    }

    function setCodigociudad($codigociudad) {
      $this->codigociudad = $codigociudad;
    }

    public function getDireccion()
    {
        return $this->direccion;
    }

    public function setDireccion(string $direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    public function getValorbeca()
    {
        return $this->valorbeca;
    }

    public function setValorbeca(int $valorbeca)
    {
        $this->valorbeca = $valorbeca;

        return $this;
    }

    public function getVigenciainicia()
    {
        return $this->vigenciainicia;
    }

    public function setVigenciainicia(int $vigenciainicia)
    {
        $this->vigenciainicia = $vigenciainicia;

        return $this;
    }

    public function getJornada()
    {
        return $this->jornada;
    }

    public function setJornada(string $jornada)
    {
        $this->jornada = $jornada;

        return $this;
    }

    public function getVigencia(): int
    {
        return $this->vigencia;
    }

    public function setVigencia(int $vigencia)
    {
        $this->vigencia = $vigencia;

        return $this;
    }

    public function getInstitucion()
    {
        return $this->institucion;
    }

    public function setInstitucion(Institucion $institucion)
    {
        $this->institucion = $institucion;

        return $this;
    }
    function getSede() {
      return $this->sede;
    }

    function setSede($sede) {
      $this->sede = $sede;
    }
    
    function getObservacion() {
      return $this->observacion;
    }

    function setObservacion($observacion) {
      $this->observacion = $observacion;
    }
    
    function getMocodmotiv() {
      return $this->mocodmotiv;
    }

    function getFechaevaluacion() {
      return $this->fechaevaluacion;
    }

    function setMocodmotiv($mocodmotiv) {
      $this->mocodmotiv = $mocodmotiv;
    }

    function setFechaevaluacion($fechaevaluacion) {
      $this->fechaevaluacion = $fechaevaluacion;
    }

    function getDocumentospresentados() {
      return $this->documentospresentados;
    }

    function setDocumentospresentados($documentospresentados) {
      $this->documentospresentados = $documentospresentados;
    }
    function getGenero() {
      return $this->genero;
    }

    function setGenero($genero) {
      $this->genero = $genero;
    }
    
    function getEnvioinvitacion() {
      return $this->envioinvitacion;
    }

    function setEnvioinvitacion($envioinvitacion) {
      $this->envioinvitacion = $envioinvitacion;
    }
    
    function getFechapostulacion() {
      return $this->fechapostulacion;
    }

    function setFechapostulacion(\Datetime $fechapostulacion) {
      $this->fechapostulacion = $fechapostulacion;
    }
    
    function getConsecutivo() {
      return $this->consecutivo;
    }

    function setConsecutivo($consecutivo) {
      $this->consecutivo = $consecutivo;
    }

    function getEsafiliado() {
        return $this->esafiliado;
    }
  
    function setEsafiliado($esafiliado) {
        $this->esafiliado = $esafiliado;
    }

    function getIdtipobeca() {
        return $this->idtipobeca;
    }
  
    function setIdtipobeca($idtipobeca) {
        $this->idtipobeca = $idtipobeca;
    }
}
