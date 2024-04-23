<?php

namespace OHMedia\VideoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use OHMedia\SecurityBundle\Entity\Traits\BlameableTrait;
use OHMedia\VideoBundle\Repository\VideoRepository;

#[ORM\Entity(repositoryClass: VideoRepository::class)]
class Video
{
    use BlameableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    public function __toString(): string
    {
        return 'Video #'.$this->id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}
