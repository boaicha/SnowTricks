<?php

namespace App\Entity;

use App\Repository\DiscussionRepository;
use DateTimeInterface;
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
    private ?DateTimeInterface $creationDate = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\ManyToOne(inversedBy: 'discussions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $iduser = null;

    #[ORM\ManyToOne(targetEntity: Trick::class, inversedBy: 'discussions')]
    #[ORM\JoinColumn(nullable: false)]
    private $trick;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreationDate(): ?DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(DateTimeInterface $creationDate): static
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

    public function getIduser(): ?User
    {
        return $this->iduser;
    }

    public function setIduser(?User $iduser): static
    {
        $this->iduser = $iduser;

        return $this;
    }

    public function getTrick(): ?trick
    {
        return $this->trick;
    }

    public function setTrick(?trick $idTrick): static
    {
        $this->trick = $idTrick;

        return $this;
    }

    public function __toString()
    {
        return $this->getContent();
    }
}
