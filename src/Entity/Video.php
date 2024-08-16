<?php

namespace OHMedia\VideoBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use OHMedia\FileBundle\Entity\File;
use OHMedia\UtilityBundle\Entity\BlameableEntityTrait;
use OHMedia\VideoBundle\Repository\VideoRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: VideoRepository::class)]
class Video
{
    use BlameableEntityTrait;

    public const TYPE_VIMEO = 'vimeo';
    public const TYPE_YOUTUBE = 'youtube';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 20)]
    private ?string $type = null;

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 20)]
    private ?string $video_id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255)]
    private ?string $thumbnail = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?File $image = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true, options: ['unsigned' => true])]
    #[Assert\Range(min: 0, max: 65535)]
    private ?int $duration = null;

    public function __toString(): string
    {
        return $this->title;
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

    public function isTypeVimeo()
    {
        return self::TYPE_VIMEO === $this->type;
    }

    public function isTypeYouTube()
    {
        return self::TYPE_YOUTUBE === $this->type;
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

    public function getHours(): int
    {
        return floor($this->duration / 3600);
    }

    public function getMinutes(): int
    {
        return floor(($this->duration % 3600) / 60);
    }

    public function getSeconds(): int
    {
        return $this->duration % 60;
    }

    public function getDurationISO8601(): string
    {
        $hours = $this->getHours();
        $minutes = $this->getMinutes();
        $seconds = $this->getSeconds();

        $duration = 'PT';

        if ($hours) {
            $duration .= $hours.'H';
        }

        if ($minutes) {
            $duration .= $minutes.'M';
        }

        $duration .= $seconds.'S';

        return $duration;
    }

    public function getDurationReadable(): string
    {
        $hours = $this->getHours();
        $minutes = $this->getMinutes();
        $seconds = $this->getSeconds();

        if ($hours && $minutes < 10) {
            $minutes = '0'.$minutes;
        }

        if ($seconds < 10) {
            $seconds = '0'.$seconds;
        }

        $parts = [$minutes, $seconds];

        if ($hours) {
            array_unshift($parts, $hours);
        }

        return implode(':', $parts);
    }

    public function getUrl(): string
    {
        if ($this->isTypeVimeo()) {
            return 'https://vimeo.com/'.$this->video_id;
        } elseif ($this->isTypeYouTube()) {
            return 'https://www.youtube.com/watch?v='.$this->video_id;
        }

        return '';
    }

    public function getEmbedUrl(): string
    {
        if ($this->isTypeVimeo()) {
            return 'https://player.vimeo.com/video/'.$this->video_id;
        } elseif ($this->isTypeYouTube()) {
            return 'https://www.youtube.com/embed/'.$this->video_id;
        }

        return '';
    }
}
