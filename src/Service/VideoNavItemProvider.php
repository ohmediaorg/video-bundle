<?php

namespace OHMedia\VideoBundle\Service\Backend\Nav;

use OHMedia\BackendBundle\Service\AbstractNavItemProvider;
use OHMedia\BootstrapBundle\Component\Nav\NavItemInterface;
use OHMedia\BootstrapBundle\Component\Nav\NavLink;
use OHMedia\VideoBundle\Entity\Video;
use OHMedia\VideoBundle\Security\Voter\VideoVoter;

class VideoNavItemProvider extends AbstractNavItemProvider
{
    public function getNavItem(): ?NavItemInterface
    {
        if ($this->isGranted(VideoVoter::INDEX, new Video())) {
            return (new NavLink('Videos', 'video_index'))
                ->setIcon('play-btn-fill');
        }

        return null;
    }
}
