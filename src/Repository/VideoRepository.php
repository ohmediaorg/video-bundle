<?php

namespace App\Repository;

use App\Entity\Video;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Video|null find($id, $lockMode = null, $lockVersion = null)
 * @method Video|null findOneBy(array $criteria, array $orderBy = null)
 * @method Video[]    findAll()
 * @method Video[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VideoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Video::class);
    }

    public function save(Video $video, bool $flush = false): void
    {
        $this->getEntityManager()->persist($video);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Video $video, bool $flush = false): void
    {
        $this->getEntityManager()->remove($video);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
