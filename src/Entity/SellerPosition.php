<?php

namespace App\Entity;

use App\Repository\SellerPositionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SellerPositionRepository::class)]
class SellerPosition
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $positionName = null;

    #[ORM\Column(length: 255)]
    private ?string $positionPriority = null;

    /**
     * @var Collection<int, Seller>
     */
    #[ORM\OneToMany(targetEntity: Seller::class, mappedBy: 'position')]
    private Collection $sellers;

    public function __construct()
    {
        $this->sellers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPositionName(): ?string
    {
        return $this->positionName;
    }

    public function setPositionName(string $positionName): static
    {
        $this->positionName = $positionName;

        return $this;
    }

    public function getPositionPriority(): ?string
    {
        return $this->positionPriority;
    }

    public function setPositionPriority(string $positionPriority): static
    {
        $this->positionPriority = $positionPriority;

        return $this;
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
            $seller->setPosition($this);
        }

        return $this;
    }

    public function removeSeller(Seller $seller): static
    {
        if ($this->sellers->removeElement($seller)) {
            // set the owning side to null (unless already changed)
            if ($seller->getPosition() === $this) {
                $seller->setPosition(null);
            }
        }

        return $this;
    }
}
