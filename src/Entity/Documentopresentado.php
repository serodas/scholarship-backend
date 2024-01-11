<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Documentospresentados
 *
 * @ORM\Table(name="documentospresentados", indexes={@ORM\Index(name="fk_documentospresentados_becados1_idx", columns={"idbecado"}), @ORM\Index(name="fk_docpresentado_requeri_idx", columns={"idrequerimiento"})})
 * @ORM\Entity
 */
class Documentopresentado
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
     * @ORM\Column(name="presentado", type="string", length=1, nullable=false, options={"default"="0"})
     */
    private $presentado = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="revisado", type="string", length=1, nullable=false, options={"default"="0"})
     */
    private $revisado = '0';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="fecharevision", type="date", nullable=true)
     */
    private $fecharevision;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nombre", type="string", length=60, nullable=true)
     */
    private $nombre;

    /**
     * @var \Requerimiento
     *
     * @ORM\ManyToOne(targetEntity="Requerimiento")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idrequerimiento", referencedColumnName="id")
     * })
     */
    private $requerimiento;

    /**
     * @var int
     *
     * @ORM\Column(name="idbecado", type="integer", nullable=false)
     */
    private $idbecado;

    public function getId(): int
    {
        return $this->id;
    }

    public function getPresentado(): string
    {
        return $this->presentado;
    }

    public function setPresentado(string $presentado): self
    {
        $this->presentado = $presentado;

        return $this;
    }

    public function getRevisado(): string
    {
        return $this->revisado;
    }

    public function setRevisado(string $revisado): self
    {
        $this->revisado = $revisado;

        return $this;
    }

    public function getFecharevision()
    {
        return $this->fecharevision;
    }

    public function setFecharevision(\DateTimeInterface $fecharevision): self
    {
        $this->fecharevision = $fecharevision;

        return $this;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getRequerimiento(): Requerimiento
    {
        return $this->requerimiento;
    }

    public function setRequerimiento(Requerimiento $requerimiento): self
    {
        $this->requerimiento = $requerimiento;

        return $this;
    }

    function getIdbecado() {
      return $this->idbecado;
    }

    function setIdbecado($idbecado) {
      $this->idbecado = $idbecado;
    }
}
