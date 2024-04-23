<?php

namespace App\Service\Backend\Nav;

use App\Entity\Video;
use App\Security\Voter\VideoVoter;
use OHMedia\BackendBundle\Service\AbstractNavItemProvider;
use OHMedia\BootstrapBundle\Component\Nav\NavItemInterface;
use OHMedia\BootstrapBundle\Component\Nav\NavLink;

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
