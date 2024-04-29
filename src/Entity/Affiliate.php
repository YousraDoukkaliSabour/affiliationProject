<?php

// src/Entity/Affiliate.php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity()
 */
class Affiliate
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
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\AffiliateLink", mappedBy="affiliate")
     */
    private $affiliateLinks;

    public function __construct()
    {
        $this->affiliateLinks = new ArrayCollection();
    }

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="affiliate")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    // Getters and setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|AffiliateLink[]
     */
    public function getAffiliateLinks(): Collection
    {
        return $this->affiliateLinks;
    }

    public function addAffiliateLink(AffiliateLink $affiliateLink): self
    {
        if (!$this->affiliateLinks->contains($affiliateLink)) {
            $this->affiliateLinks[] = $affiliateLink;
            $affiliateLink->setAffiliate($this);
        }

        return $this;
    }

    public function removeAffiliateLink(AffiliateLink $affiliateLink): self
    {
        if ($this->affiliateLinks->contains($affiliateLink)) {
            $this->affiliateLinks->removeElement($affiliateLink);
            // set the owning side to null (unless already changed)
            if ($affiliateLink->getAffiliate() === $this) {
                $affiliateLink->setAffiliate(null);
            }
        }

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