<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class AffiliateLink
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Affiliate", inversedBy="affiliateLinks")

     */
    private $affiliate;






    /**
     * @ORM\Column(type="string", length=255)
     */
    private $link;

    /**
     * @ORM\Column(type="integer")
     */
    private $salesCount = 0;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", cascade={"persist"})
     */
    private $user;

    /**
     * @ORM\Column(type="float")
     */
    private $plan1;

    /**
     * @ORM\Column(type="float")
     */
    private $plan2;

    /**
     * @ORM\Column(type="float")
     */
    private $plan3;

    /**
     * @ORM\Column(type="float")
     */
    private $plan4;

    /**
     * @ORM\Column(type="float")
     */
    private $plan5;

    /**
     * @ORM\Column(type="float")
     */
    private $plan6;

    /**
     * @ORM\Column(type="float")
     */
    private $plan7;

    /**
     * @ORM\Column(type="float")
     */
    private $plan8;






    // Getters and setters
    public function getAffiliate(): ?Affiliate
    {
        return $this->affiliate;
    }

    public function setAffiliate(?Affiliate $affiliate): self
    {
        $this->affiliate = $affiliate;

        return $this;
    }




    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): self
    {
        $this->link = $link;

        return $this;
    }


    public function getId(): ?string
    {
        return $this->id;
    }

    public function getSalesCount(): ?int
    {
        return $this->salesCount;
    }

    public function setSalesCount(int $salesCount): self
    {
        $this->salesCount = $salesCount;

        return $this;
    }

    public function incrementSalesCount(): self
    {
        $this->salesCount++;

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

    public function getPlan1(): ?float
    {
        return $this->plan1;
    }

    public function setPlan1(float $plan1): self
    {
        $this->plan1 = $plan1;

        return $this;
    }

    public function getPlan2(): ?float
    {
        return $this->plan2;
    }

    public function setPlan2(float $plan2): self
    {
        $this->plan2 = $plan2;

        return $this;
    }

    public function getPlan3(): ?float
    {
        return $this->plan3;
    }

    public function setPlan3(float $plan3): self
    {
        $this->plan3 = $plan3;

        return $this;
    }

    public function getPlan4(): ?float
    {
        return $this->plan4;
    }

    public function setPlan4(float $plan4): self
    {
        $this->plan4 = $plan4;

        return $this;
    }

    public function getPlan5(): ?float
    {
        return $this->plan5;
    }

    public function setPlan5(float $plan5): self
    {
        $this->plan5 = $plan5;

        return $this;
    }

    public function getPlan6(): ?float
    {
        return $this->plan6;
    }

    public function setPlan6(float $plan6): self
    {
        $this->plan6 = $plan6;

        return $this;
    }

    public function getPlan7(): ?float
    {
        return $this->plan7;
    }

    public function setPlan7(float $plan7): self
    {
        $this->plan7 = $plan7;

        return $this;
    }

    public function getPlan8(): ?float
    {
        return $this->plan8;
    }

    public function setPlan8(float $plan8): self
    {
        $this->plan8 = $plan8;

        return $this;
    }




}
