<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TopicRepository")
 */
class Topic
{
    /**
     * @ORM\Id()
     *
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Type")
     *
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Topic", inversedBy="children", cascade={"persist", "remove"})
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Topic", mappedBy="parent")
     *
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $children;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\DataEntry", mappedBy="topic", orphanRemoval=true, cascade={"persist", "remove"})
     */
    private $dataEntries;

    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->dataEntries = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        $this->id = $id;
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

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(?Type $type): self
    {
        $this->type = $type;

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
     * @return Collection|self[]
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

    /**
     * @return Collection|DataEntry[]
     */
    public function getDataEntries(): Collection
    {
        return $this->dataEntries;
    }

    public function addDataEntry(DataEntry $dataEntry): self
    {
        if (!$this->dataEntries->contains($dataEntry)) {
            $this->dataEntries[] = $dataEntry;
            $dataEntry->setTopic($this);
        }

        return $this;
    }

    public function removeDataEntry(DataEntry $dataEntry): self
    {
        if ($this->dataEntries->contains($dataEntry)) {
            $this->dataEntries->removeElement($dataEntry);
            // set the owning side to null (unless already changed)
            if ($dataEntry->getTopic() === $this) {
                $dataEntry->setTopic(null);
            }
        }

        return $this;
    }

    public function hasDescendant(self $topic)
    {
        if ($this->getId() == $topic->getId()) {
            return true;
        }

        foreach ($this->getChildren() as $child) {
            if ($child->hasDescendant($topic)) {
                return true;
            }
        }

        return false;
    }

    public function __toString()
    {
        return $this->name;
    }
}
