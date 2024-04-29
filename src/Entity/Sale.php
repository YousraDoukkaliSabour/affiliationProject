<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SaleRepository")
 */
class Sale
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="string")
     */
    private $visitorIdentifier;

    /**
     * @ORM\Column(type="string")
     */
    private $pricingPlans ;

    /**
     * @ORM\Column(type="boolean")
     */
    private $commissionsCalculated = false;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getVisitorIdentifier(): ?string
    {
        return $this->visitorIdentifier;
    }

    public function setVisitorIdentifier(string $visitorIdentifier): self
    {
        $this->visitorIdentifier = $visitorIdentifier;

        return $this;
    }

    public function getPricingPlans(): ?string
    {
        return $this->pricingPlans;
    }

    public function setPricingPlans(string $pricingPlans): self
    {
        $this->pricingPlans = $pricingPlans;

        return $this;
    }
    public function getCommissionsCalculated(): bool
    {
        return $this->commissionsCalculated;
    }

    public function setCommissionsCalculated(bool $commissionsCalculated): self
    {
        $this->commissionsCalculated = $commissionsCalculated;
        return $this;
    }
}