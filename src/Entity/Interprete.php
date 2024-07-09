<?php

namespace App\Entity;

use App\Repository\InterpreteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InterpreteRepository::class)]
class Interprete
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    /**
     * @var Collection<int, Musique>
     */
    #[ORM\OneToMany(targetEntity: Musique::class, mappedBy: 'interprete')]
    private Collection $musiques;

    public function __construct()
    {
        $this->musiques = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection<int, Musique>
     */
    public function getMusiques(): Collection
    {
        return $this->musiques;
    }

    public function addMusique(Musique $musique): static
    {
        if (!$this->musiques->contains($musique)) {
            $this->musiques->add($musique);
            $musique->setInterprete($this);
        }

        return $this;
    }

    public function removeMusique(Musique $musique): static
    {
        if ($this->musiques->removeElement($musique)) {
            // set the owning side to null (unless already changed)
            if ($musique->getInterprete() === $this) {
                $musique->setInterprete(null);
            }
        }

        return $this;
    }
}
