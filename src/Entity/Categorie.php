<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieRepository::class)]
class Categorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    /**
     * @var Collection<int, Knowledge>
     */
    #[ORM\OneToMany(targetEntity: Knowledge::class, mappedBy: 'categorie')]
    private Collection $knowledges;

    public function __construct()
    {
        $this->knowledges = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Knowledge>
     */
    public function getKnowledges(): Collection
    {
        return $this->knowledges;
    }

    public function addKnowledge(Knowledge $knowledge): static
    {
        if (!$this->knowledges->contains($knowledge)) {
            $this->knowledges->add($knowledge);
            $knowledge->setCategorie($this);
        }

        return $this;
    }

    public function removeKnowledge(Knowledge $knowledge): static
    {
        if ($this->knowledges->removeElement($knowledge)) {
            // set the owning side to null (unless already changed)
            if ($knowledge->getCategorie() === $this) {
                $knowledge->setCategorie(null);
            }
        }

        return $this;
    }
}
