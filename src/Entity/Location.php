<?php

namespace App\Entity;

use App\Repository\LocationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LocationRepository::class)]
class Location implements \Stringable
{
    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 150)]
    private ?string $name = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'children', cascade: ['persist', 'remove'])]
    private ?Location $parent = null;

    /**
     * @var Collection<int, \App\Entity\Location>
     */
    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'parent')]
    #[ORM\OrderBy(['name' => 'ASC'])]
    private Collection $children;

    #[ORM\Column(type: Types::BOOLEAN)]
    private ?bool $isStartNode = null;

    #[ORM\JoinColumn(nullable: false)]
    #[ORM\ManyToOne(targetEntity: Type::class)]
    private ?Type $type = null;

    /**
     * @var Collection<int, DataEntry>
     */
    #[ORM\OneToMany(targetEntity: DataEntry::class, mappedBy: 'location', orphanRemoval: true, cascade: ['persist', 'remove'])]
    private Collection $dataEntries;

    public function __construct()
    {
        $this->isStartNode = false;
        $this->children = new ArrayCollection();
        $this->dataEntries = new ArrayCollection();
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

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

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection<int, self>|self[]
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(self $child): self
    {
        if (!$this->children->contains($child)) {
            $this->children[] = $child;
            $child->setParent($this);
        }

        return $this;
    }

    public function removeChild(self $child): self
    {
        if ($this->children->contains($child)) {
            $this->children->removeElement($child);
            // set the owning side to null (unless already changed)
            if ($child->getParent() === $this) {
                $child->setParent(null);
            }
        }

        return $this;
    }

    public function getIsStartNode(): ?bool
    {
        return $this->isStartNode;
    }

    public function setIsStartNode(bool $isStartNode): self
    {
        $this->isStartNode = $isStartNode;

        return $this;
    }

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(?Type $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, DataEntry>|DataEntry[]
     */
    public function getDataEntries(): Collection
    {
        return $this->dataEntries;
    }

    public function addDataEntry(DataEntry $dataEntry): self
    {
        if (!$this->dataEntries->contains($dataEntry)) {
            $this->dataEntries[] = $dataEntry;
            $dataEntry->setLocation($this);
        }

        return $this;
    }

    public function removeDataEntry(DataEntry $dataEntry): self
    {
        if ($this->dataEntries->contains($dataEntry)) {
            $this->dataEntries->removeElement($dataEntry);
            // set the owning side to null (unless already changed)
            if ($dataEntry->getLocation() === $this) {
                $dataEntry->setLocation(null);
            }
        }

        return $this;
    }

    public function hasDescendant(self $location): bool
    {
        if ($this->getId() == $location->getId()) {
            return true;
        }

        foreach ($this->getChildren() as $child) {
            if ($child->hasDescendant($location)) {
                return true;
            }
        }

        return false;
    }

    #[\Override]
    public function __toString(): string
    {
        return $this->name.' <i>('.$this->type.')</i>';
    }
}
