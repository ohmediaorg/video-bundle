<?php

namespace OHMedia\VideoBundle\Security\Voter;

use OHMedia\SecurityBundle\Entity\User;
use OHMedia\SecurityBundle\Security\Voter\AbstractEntityVoter;
use OHMedia\VideoBundle\Entity\Video;

class VideoVoter extends AbstractEntityVoter
{
    public const INDEX = 'index';
    public const CREATE = 'create';
    public const EDIT = 'edit';
    public const DELETE = 'delete';

    protected function getAttributes(): array
    {
        return [
            self::INDEX,
            self::CREATE,
            self::EDIT,
            self::DELETE,
        ];
    }

    protected function getEntityClass(): string
    {
        return Video::class;
    }

    protected function canIndex(Video $video, User $loggedIn): bool
    {
        return true;
    }

    protected function canCreate(Video $video, User $loggedIn): bool
    {
        return true;
    }

    protected function canEdit(Video $video, User $loggedIn): bool
    {
        return true;
    }

    protected function canDelete(Video $video, User $loggedIn): bool
    {
        return true;
    }
}
