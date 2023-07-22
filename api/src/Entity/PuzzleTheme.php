<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\PuzzleThemeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PuzzleThemeRepository::class)]
#[ApiResource]

class PuzzleTheme
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Unique]
    private $name;

    #[ORM\ManyToMany(targetEntity: Puzzle::class, mappedBy: 'themes')]
    private $puzzles;

    public function __construct()
    {
        $this->puzzles = new ArrayCollection();
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

    /**
     * @return Collection<int, Puzzle>
     */
    public function getPuzzles(): Collection
    {
        return $this->puzzles;
    }

    public function addPuzzle(Puzzle $puzzle): self
    {
        if (!$this->puzzles->contains($puzzle)) {
            $this->puzzles[] = $puzzle;
            $puzzle->addTheme($this);
        }

        return $this;
    }

    public function removePuzzle(Puzzle $puzzle): self
    {
        if ($this->puzzles->removeElement($puzzle)) {
            $puzzle->removeTheme($this);
        }

        return $this;
    }
}
