<?php

namespace App\Entity;

use App\Repository\DiscussionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DiscussionRepository::class)]
class Discussion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $creationDate = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\ManyToOne(inversedBy: 'discussions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?user $iduser = null;

    #[ORM\ManyToOne(inversedBy: 'discussions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?trick $idTrick = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeInterface $creationDate): static
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getIduser(): ?user
    {
        return $this->iduser;
    }

    public function setIduser(?user $iduser): static
    {
        $this->iduser = $iduser;

        return $this;
    }

    public function getIdTrick(): ?trick
    {
        return $this->idTrick;
    }

    public function setIdTrick(?trick $idTrick): static
    {
        $this->idTrick = $idTrick;

        return $this;
    }
}
