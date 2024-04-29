<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommissionTotalRepository")
 */
class CommissionTotal
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    // Ajoutez la relation ManyToOne vers l'entité User
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\Column(type="integer")
     */
    private $affiliateLinkId;

    /**
     * @ORM\Column(type="float")
     */
    private $totalAmount;


    /**
     * @ORM\Column(type="boolean")
     */
    private $commissionRequested = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAffiliateLinkId(): ?int
    {
        return $this->affiliateLinkId;
    }

    public function setAffiliateLinkId(int $affiliateLinkId): self
    {
        $this->affiliateLinkId = $affiliateLinkId;

        return $this;
    }

    public function getTotalAmount(): ?float
    {
        return $this->totalAmount;
    }

    public function setTotalAmount(float $totalAmount): self
    {
        $this->totalAmount = $totalAmount;

        return $this;
    }
    public function getCommissionRequested(): ?bool
    {
        return $this->commissionRequested;
    }

    public function setCommissionRequested(bool $commissionRequested): self
    {
        $this->commissionRequested = $commissionRequested;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }
}