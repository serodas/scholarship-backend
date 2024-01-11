<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Authorities
 *
 * @ORM\Table(name="authorities", uniqueConstraints={@ORM\UniqueConstraint(name="user_id_authority_unique", columns={"user_id", "authority"})}, indexes={@ORM\Index(name="IDX_991762E5A76ED395", columns={"user_id"})})
 * @ORM\Entity
 */
class Authorities
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
     * @ORM\Column(name="authority", type="string", length=45, nullable=false)
     */
    private $authority;

    /**
     * @var \Users
     *
     * @ORM\ManyToOne(targetEntity="Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    public function getId(): int
    {
        return $this->id;
    }

    public function getAuthority(): string
    {
        return $this->authority;
    }

    public function setAuthority(string $authority): self
    {
        $this->authority = $authority;

        return $this;
    }

    public function getUser(): Users
    {
        return $this->user;
    }

    public function setUser(Users $user): self
    {
        $this->user = $user;

        return $this;
    }


}
