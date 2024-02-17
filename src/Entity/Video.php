<?php

namespace App\Entity;

use App\Repository\VideoRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Va\Constraints as CustomAssert;

#[ORM\Entity(repositoryClass: VideoRepository::class)]
class Video
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @Assert\NotBlank
     * @Assert\Url(
     *     protocols = {"http", "https"},
     *     message = "Invalid YouTube video URL."
     * )
     */

    #[ORM\Column(length: 255)]
    #[Assert\Url(
        groups: ["creation"],
        message: 'URL non valide',
    )]
    private ?string $video = null;

    #[ORM\ManyToOne(inversedBy: 'videos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?trick $idTrick = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVideo(): ?string
    {
        return $this->video;
    }

    public function setVideo(string $video): static
    {
        $this->video = $video;

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
