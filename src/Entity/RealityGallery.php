<?php

namespace App\Entity;

use App\Repository\RealityGalleryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RealityGalleryRepository::class)]
class RealityGallery
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Reality $reality = null;

    /**
     * @var Collection<int, RealityGalleryItem>
     */
    #[ORM\OneToMany(targetEntity: RealityGalleryItem::class, mappedBy: 'gallery')]
    private Collection $realityGalleryItems;

    public function __construct()
    {
        $this->realityGalleryItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReality(): ?Reality
    {
        return $this->reality;
    }

    public function setReality(?Reality $reality): static
    {
        $this->reality = $reality;

        return $this;
    }

    /**
     * @return Collection<int, RealityGalleryItem>
     */
    public function getRealityGalleryItems(): Collection
    {
        return $this->realityGalleryItems;
    }

    public function addRealityGalleryItem(RealityGalleryItem $realityGalleryItem): static
    {
        if (!$this->realityGalleryItems->contains($realityGalleryItem)) {
            $this->realityGalleryItems->add($realityGalleryItem);
            $realityGalleryItem->setGallery($this);
        }

        return $this;
    }

    public function removeRealityGalleryItem(RealityGalleryItem $realityGalleryItem): static
    {
        if ($this->realityGalleryItems->removeElement($realityGalleryItem)) {
            // set the owning side to null (unless already changed)
            if ($realityGalleryItem->getGallery() === $this) {
                $realityGalleryItem->setGallery(null);
            }
        }

        return $this;
    }
}
