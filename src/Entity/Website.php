<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WebsiteRepository")
 */
class Website
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $domain;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ip;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TlsScanResult", mappedBy="website", orphanRemoval=true)
     */
    private $tlsScanResults;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $port;

    public function __construct()
    {
        $this->validFrom = new ArrayCollection();
        $this->tlsScanResults = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDomain(): ?string
    {
        return $this->domain;
    }

    public function setDomain(string $domain): self
    {
        $this->domain = $domain;

        return $this;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(?string $ip): self
    {
        $this->ip = $ip;

        return $this;
    }

    public function hasIp() : bool
    {
        return !is_null($this->getIp());
    }

    /**
     * @return Collection|TlsScanResult[]
     */
    public function getTlsScanResults(): Collection
    {
        return $this->tlsScanResults;
    }

    public function addTlsScanResult(TlsScanResult $tlsScanResult): self
    {
        if (!$this->tlsScanResults->contains($tlsScanResult)) {
            $this->tlsScanResults[] = $tlsScanResult;
            $tlsScanResult->setWebsite($this);
        }

        return $this;
    }

    public function removeTlsScanResult(TlsScanResult $tlsScanResult): self
    {
        if ($this->tlsScanResults->contains($tlsScanResult)) {
            $this->tlsScanResults->removeElement($tlsScanResult);
            // set the owning side to null (unless already changed)
            if ($tlsScanResult->getWebsite() === $this) {
                $tlsScanResult->setWebsite(null);
            }
        }

        return $this;
    }

    public function getPort(): ?int
    {
        return $this->port;
    }

    public function setPort(?int $port): self
    {
        $this->port = $port;

        return $this;
    }
}
