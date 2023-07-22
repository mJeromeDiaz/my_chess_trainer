<?php

namespace App\Entity;

use App\Repository\PuzzleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;

#[ORM\Entity(repositoryClass: PuzzleRepository::class)]
#[ApiResource]
class Puzzle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $lichessId;

    #[ORM\Column(type: 'text')]
    private $fen;

    #[ORM\Column(type: 'text')]
    private $moves;

    #[ORM\Column(type: 'string', length: 255)]
    private $rating;


    #[ORM\Column(type: 'string', length: 255)]
    private $popularity;

    #[ORM\Column(type: 'string', length: 255)]
    private $nbplays;

    #[ORM\Column(type: 'string', length: 255)]
    private $ratingdeviation;

    #[ORM\ManyToMany(targetEntity: PuzzleTheme::class, inversedBy: 'puzzles')]
    private $themes;

    #[ORM\ManyToMany(targetEntity: PuzzleOpening::class, inversedBy: 'puzzles')]
    private $openings;

    #[ORM\Column(type: 'string', length: 255)]
    private $gameurl;

    public function __construct()
    {
        $this->themes = new ArrayCollection();
        $this->openings = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLichessId(): ?string
    {
        return $this->lichessId;
    }

    public function setLichessId(string $lichessId): self
    {
        $this->lichessId = $lichessId;

        return $this;
    }

    public function getFen(): ?string
    {
        return $this->fen;
    }

    public function setFen(string $fen): self
    {
        $this->fen = $fen;

        return $this;
    }

    public function getMoves(): ?string
    {
        return $this->moves;
    }

    public function setMoves(string $moves): self
    {
        $this->moves = $moves;

        return $this;
    }

    public function getRating(): ?string
    {
        return $this->rating;
    }

    public function setRating(string $rating): self
    {
        $this->rating = $rating;

        return $this;
    }


    public function getPopularity(): ?string
    {
        return $this->popularity;
    }

    public function setPopularity(string $popularity): self
    {
        $this->popularity = $popularity;

        return $this;
    }

    public function getNbplays(): ?string
    {
        return $this->nbplays;
    }

    public function setNbplays(string $nbplays): self
    {
        $this->nbplays = $nbplays;

        return $this;
    }

    public function getRatingdeviation(): ?string
    {
        return $this->ratingdeviation;
    }

    public function setRatingdeviation(string $ratingdeviation): self
    {
        $this->ratingdeviation = $ratingdeviation;

        return $this;
    }

    /**
     * @return Collection<int, PuzzleTheme>
     */
    public function getThemes(): Collection
    {
        return $this->themes;
    }

    public function addTheme(PuzzleTheme $theme): self
    {
        if (!$this->themes->contains($theme)) {
            $this->themes[] = $theme;
        }

        return $this;
    }

    public function removeTheme(PuzzleTheme $theme): self
    {
        $this->themes->removeElement($theme);

        return $this;
    }

    /**
     * @return Collection<int, PuzzleOpening>
     */
    public function getOpenings(): Collection
    {
        return $this->openings;
    }

    public function addOpening(PuzzleOpening $opening): self
    {
        if (!$this->openings->contains($opening)) {
            $this->openings[] = $opening;
        }

        return $this;
    }

    public function removeOpening(PuzzleOpening $opening): self
    {
        $this->openings->removeElement($opening);

        return $this;
    }

    public function getGameurl(): ?string
    {
        return $this->gameurl;
    }

    public function setGameurl(string $gameurl): self
    {
        $this->gameurl = $gameurl;

        return $this;
    }



}
