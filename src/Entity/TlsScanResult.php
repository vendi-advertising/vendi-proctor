<?php

declare(strict_types=1);

namespace App\Entity;

use App\CertificateValidators\CertificateValidatorInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TlsScanResultRepository")
 */
class TlsScanResult
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Website", inversedBy="tlsScanResults")
     * @ORM\JoinColumn(nullable=false)
     */
    private $website;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateValidFrom;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateValidTo;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $rawTlsData = [];

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateTimeCreated;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $hostnameTested;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ipTested;

    /**
     * @ORM\Column(type="string", length=1024, nullable=true)
     */
    private $failReason;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    public function __construct()
    {
        $this->dateTimeCreated = new \DateTime();
    }

    public function set_status_error()
    {
        $this->setStatus(CertificateValidatorInterface::STATUS_ERROR);
    }

    public function set_status_valid()
    {
        $this->setStatus(CertificateValidatorInterface::STATUS_VALID);
    }

    public function set_status_warning()
    {
        $this->setStatus(CertificateValidatorInterface::STATUS_WARNING);
    }

    public function get_is_valid() : bool
    {
        return CertificateValidatorInterface::STATUS_VALID === $this->status;
    }

    public function getDateTimeCreated() : \DateTime
    {
        return $this->dateTimeCreated;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWebsite(): ?Website
    {
        return $this->website;
    }

    public function setWebsite(?Website $website): self
    {
        $this->website = $website;

        return $this;
    }

    public function getDateValidFrom(): ?\DateTimeInterface
    {
        return $this->dateValidFrom;
    }

    public function setDateValidFrom(\DateTimeInterface $dateValidFrom): self
    {
        $this->dateValidFrom = $dateValidFrom;

        return $this;
    }

    public function getDateValidTo(): ?\DateTimeInterface
    {
        return $this->dateValidTo;
    }

    public function setDateValidTo(\DateTimeInterface $dateValidTo): self
    {
        $this->dateValidTo = $dateValidTo;

        return $this;
    }

    public function getRawTlsData(): ?array
    {
        return $this->rawTlsData;
    }

    public function setRawTlsData(?array $rawTlsData): self
    {
        $this->rawTlsData = $rawTlsData;

        return $this;
    }

    public function getHostnameTested(): ?string
    {
        return $this->hostnameTested;
    }

    public function setHostnameTested(string $hostnameTested): self
    {
        $this->hostnameTested = $hostnameTested;

        return $this;
    }

    public function getIpTested(): ?string
    {
        return $this->ipTested;
    }

    public function setIpTested(string $ipTested): self
    {
        $this->ipTested = $ipTested;

        return $this;
    }

    public function getFailReason(): ?string
    {
        return $this->failReason;
    }

    public function setFailReason(?string $failReason): self
    {
        $this->failReason = $failReason;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }
}
