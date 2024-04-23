<?php

namespace OHMedia\VideoBundle\Service;

use OHMedia\SecurityBundle\Service\EntityChoiceInterface;
use OHMedia\VideoBundle\Entity\Video;

class VideoEntityChoice implements EntityChoiceInterface
{
    public function getLabel(): string
    {
        return 'Videos';
    }

    public function getEntities(): array
    {
        return [Video::class];
    }
}
