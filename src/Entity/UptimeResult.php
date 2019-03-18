<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\AgentTests\AgentTestInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UptimeResultRepository")
 */
class UptimeResult implements TestResultInterface
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
    private $failReason;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateTimeCreated;

    public function __construct()
    {
        $this->dateTimeCreated = new \DateTime();
    }

    public function set_status_error()
    {
        $this->setStatus(AgentTestInterface::STATUS_ERROR);
    }

    public function set_status_valid()
    {
        $this->setStatus(AgentTestInterface::STATUS_VALID);
    }

    public function set_status_warning()
    {
        $this->setStatus(AgentTestInterface::STATUS_WARNING);
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

    public function getFailReason(): ?string
    {
        return $this->failReason;
    }

    public function setFailReason(?string $failReason): TestResultInterface
    {
        $this->failReason = $failReason;

        return $this;
    }

    public function get_is_valid() : bool
    {
        return AgentTestInterface::STATUS_VALID === $this->status;
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
