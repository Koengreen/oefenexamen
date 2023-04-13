<?php

namespace App\Entity;

use App\Repository\GenreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GenreRepository::class)]
class Genre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'genre', targetEntity: Serie::class)]
    private Collection $serie;

    public function __construct()
    {
        $this->serie = new ArrayCollection();
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
     * @return Collection<int, serie>
     */
    public function getSerie(): Collection
    {
        return $this->serie;
    }

    public function addSerie(serie $serie): self
    {
        if (!$this->serie->contains($serie)) {
            $this->serie->add($serie);
            $serie->setGenre($this);
        }

        return $this;
    }

    public function removeSerie(serie $serie): self
    {
        if ($this->serie->removeElement($serie)) {
            // set the owning side to null (unless already changed)
            if ($serie->getGenre() === $this) {
                $serie->setGenre(null);
            }
        }

        return $this;
    }
}
