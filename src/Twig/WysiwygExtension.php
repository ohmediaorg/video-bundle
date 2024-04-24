<?php

namespace OHMedia\VideoBundle\Twig;

use OHMedia\FileBundle\Service\FileManager;
use OHMedia\VideoBundle\Entity\Video;
use OHMedia\VideoBundle\Repository\VideoRepository;
use OHMedia\WysiwygBundle\Twig\AbstractWysiwygExtension;
use Symfony\Component\HttpFoundation\UrlHelper;
use Twig\Environment;
use Twig\TwigFunction;

class WysiwygExtension extends AbstractWysiwygExtension
{
    private array $schemas = [];

    public function __construct(
        private FileManager $fileManager,
        private UrlHelper $urlHelper,
        private VideoRepository $videoRepository
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('video', [$this, 'video'], [
                'is_safe' => ['html'],
                'needs_environment' => true,
            ]),
        ];
    }

    public function video(Environment $twig, int $id = null)
    {
        $video = $this->videoRepository->find($id);

        if (!$video) {
            return '';
        }

        $rendered = $twig->render('@OHMediaVideo/video.html.twig', [
            'video' => $video,
        ]);

        if (!isset($this->schemas[$id])) {
            $this->schemas[$id] = true;

            $rendered .= $this->getSchema($video);
        }

        return $rendered;
    }

    private function getSchema(Video $video)
    {
        $image = $video->getImage();

        if ($image && $image->getPath()) {
            $path = $this->fileManager->getWebPath($video->getImage());

            $thumbnailUrl = [$this->urlHelper->getAbsoluteUrl($path)];
        } elseif ($video->getThumbnail()) {
            $thumbnailUrl = [$video->getThumbnail()];
        } else {
            $thumbnailUrl = [];
        }

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'VideoObject',
            'name' => $video->getTitle(),
            'thumbnailUrl' => $thumbnailUrl,
            'duration' => $video->getDurationISO8601(),
            'contentUrl' => $video->getUrl(),
            'embedUrl' => $video->getEmbedUrl(),
        ];

        return '<script type="application/ld+json">'.json_encode($schema).'</script>';
    }
}
