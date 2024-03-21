<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DataEntryRepository")
 */
class DataEntry
{
    /**
     * @ORM\Id()
     *
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="smallint")
     */
    private $yearFrom;

    /**
     * @ORM\Column(type="smallint")
     */
    private $yearTo;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Topic", inversedBy="dataEntries")
     *
     * @ORM\JoinColumn(nullable=false)
     */
    private $topic;

    /**
     * @ORM\Column(type="decimal", precision=18, scale=9)
     */
    private $value;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Location", inversedBy="dataEntries")
     *
     * @ORM\JoinColumn(nullable=false)
     */
    private $location;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getYearFrom(): ?int
    {
        return $this->yearFrom;
    }

    public function setYearFrom(int $yearFrom): self
    {
        $this->yearFrom = $yearFrom;

        return $this;
    }

    public function getYearTo(): ?int
    {
        return $this->yearTo;
    }

    public function setYearTo(int $yearTo): self
    {
        $this->yearTo = $yearTo;

        return $this;
    }

    public function getTopic(): ?Topic
    {
        return $this->topic;
    }

    public function setTopic(?Topic $topic): self
    {
        $this->topic = $topic;

        return $this;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): self
    {
        $this->location = $location;

        return $this;
    }
}
