<?php

namespace App\Service\EntityChoice;

use App\Entity\Video;
use OHMedia\SecurityBundle\Service\EntityChoiceInterface;

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
