<?php

namespace App\Entity;

use App\Repository\ContactsPageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContactsPageRepository::class)]
class ContactsPage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, Seller>
     */
    #[ORM\OneToMany(targetEntity: Seller::class, mappedBy: 'contactsPage')]
    private Collection $sellers;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $contactMail = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $contactPhone = null;

    #[ORM\Column(length: 255)]
    private ?string $contactPageText = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $companyCity = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $companyStreet = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $companyPSC = null;

    #[ORM\Column(nullable: true)]
    private ?float $companyLocationLat = null;

    #[ORM\Column(nullable: true)]
    private ?float $companyLocationLng = null;

    public function __construct()
    {
        $this->sellers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Seller>
     */
    public function getSellers(): Collection
    {
        return $this->sellers;
    }

    public function addSeller(Seller $seller): static
    {
        if (!$this->sellers->contains($seller)) {
            $this->sellers->add($seller);
            $seller->setContactsPage($this);
        }

        return $this;
    }

    public function removeSeller(Seller $seller): static
    {
        if ($this->sellers->removeElement($seller)) {
            // set the owning side to null (unless already changed)
            if ($seller->getContactsPage() === $this) {
                $seller->setContactsPage(null);
            }
        }

        return $this;
    }

    public function getContactMail(): ?string
    {
        return $this->contactMail;
    }

    public function setContactMail(?string $contactMail): static
    {
        $this->contactMail = $contactMail;

        return $this;
    }

    public function getContactPhone(): ?string
    {
        return $this->contactPhone;
    }

    public function setContactPhone(?string $contactPhone): static
    {
        $this->contactPhone = $contactPhone;

        return $this;
    }

    public function getContactPageText(): ?string
    {
        return $this->contactPageText;
    }

    public function setContactPageText(string $contactPageText): static
    {
        $this->contactPageText = $contactPageText;

        return $this;
    }

    public function getCompanyCity(): ?string
    {
        return $this->companyCity;
    }

    public function setCompanyCity(?string $companyCity): static
    {
        $this->companyCity = $companyCity;

        return $this;
    }

    public function getCompanyStreet(): ?string
    {
        return $this->companyStreet;
    }

    public function setCompanyStreet(?string $companyStreet): static
    {
        $this->companyStreet = $companyStreet;

        return $this;
    }

    public function getCompanyPSC(): ?string
    {
        return $this->companyPSC;
    }

    public function setCompanyPSC(?string $companyPSC): static
    {
        $this->companyPSC = $companyPSC;

        return $this;
    }

    public function getCompanyLocationLat(): ?float
    {
        return $this->companyLocationLat;
    }

    public function setCompanyLocationLat(?float $companyLocationLat): static
    {
        $this->companyLocationLat = $companyLocationLat;

        return $this;
    }

    public function getCompanyLocationLng(): ?float
    {
        return $this->companyLocationLng;
    }

    public function setCompanyLocationLng(?float $companyLocationLng): static
    {
        $this->companyLocationLng = $companyLocationLng;

        return $this;
    }
}
