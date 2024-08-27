<?php

namespace OHMedia\VideoBundle\Service;

use OHMedia\VideoBundle\Repository\VideoRepository;
use OHMedia\WysiwygBundle\Shortcodes\AbstractShortcodeProvider;
use OHMedia\WysiwygBundle\Shortcodes\Shortcode;

class VideoShortcodeProvider extends AbstractShortcodeProvider
{
    public function __construct(private VideoRepository $videoRepository)
    {
    }

    public function getTitle(): string
    {
        return 'Videos';
    }

    public function buildShortcodes(): void
    {
        $videos = $this->videoRepository->createQueryBuilder('v')
            ->orderBy('v.title', 'asc')
            ->getQuery()
            ->getResult();

        foreach ($videos as $video) {
            $id = $video->getId();

            $this->addShortcode(new Shortcode(
                sprintf('%s (ID:%s)', $video, $id),
                'video('.$id.')'
            ));
        }
    }
}
