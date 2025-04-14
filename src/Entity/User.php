<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $passWord = null;

    #[ORM\Column(length: 255)]
    private ?string $role = null;

    /**
     * @var Collection<int, Knowledge>
     */
    #[ORM\OneToMany(targetEntity: Knowledge::class, mappedBy: 'author')]
    private Collection $knowledges;

    public function __construct()
    {
        $this->knowledges = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassWord(): ?string
    {
        return $this->passWord;
    }

    public function setPassWord(string $passWord): static
    {
        $this->passWord = $passWord;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;

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
            $knowledge->setAuthor($this);
        }

        return $this;
    }

    public function removeKnowledge(Knowledge $knowledge): static
    {
        if ($this->knowledges->removeElement($knowledge)) {
            // set the owning side to null (unless already changed)
            if ($knowledge->getAuthor() === $this) {
                $knowledge->setAuthor(null);
            }
        }

        return $this;
    }
}
