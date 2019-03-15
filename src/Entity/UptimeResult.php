<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UptimeResultRepository")
 */
class UptimeResult
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Website", inversedBy="uptimeResults")
     * @ORM\JoinColumn(nullable=false)
     */
    private $website;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $httpStatus;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=7, nullable=true)
     */
    private $loadTimeInMs;

    /**
     * @ORM\Column(type="string", length=1024, nullable=true)
     */
    private $errorMessage;

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

    public function getHttpStatus(): ?int
    {
        return $this->httpStatus;
    }

    public function setHttpStatus(?int $httpStatus): self
    {
        $this->httpStatus = $httpStatus;

        return $this;
    }

    public function getLoadTimeInMs()
    {
        return $this->loadTimeInMs;
    }

    public function setLoadTimeInMs($loadTimeInMs): self
    {
        $this->loadTimeInMs = $loadTimeInMs;

        return $this;
    }

    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }

    public function setErrorMessage(?string $errorMessage): self
    {
        $this->errorMessage = $errorMessage;

        return $this;
    }

}
