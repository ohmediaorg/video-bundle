<?php

namespace OHMedia\VideoBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use OHMedia\FileBundle\Entity\File;
use OHMedia\SecurityBundle\Entity\Traits\BlameableTrait;
use OHMedia\VideoBundle\Repository\VideoRepository;

#[ORM\Entity(repositoryClass: VideoRepository::class)]
class Video
{
    use BlameableTrait;

    public const TYPE_VIMEO = 'vimeo';
    public const TYPE_YOUTUBE = 'youtube';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 20)]
    private ?string $type = null;

    #[ORM\Column(length: 20)]
    private ?string $video_id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $thumbnail = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?File $image = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true, options: ['unsigned' => true])]
    private ?int $duration = null;

    public function __toString(): string
    {
        return 'Video #'.$this->id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getVideoId(): ?string
    {
        return $this->video_id;
    }

    public function setVideoId(string $video_id): static
    {
        $this->video_id = $video_id;

        return $this;
    }

    public function getThumbnail(): ?string
    {
        return $this->thumbnail;
    }

    public function setThumbnail(?string $thumbnail): static
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    public function getImage(): ?File
    {
        return $this->image;
    }

    public function setImage(?File $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }
}
