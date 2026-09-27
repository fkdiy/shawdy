<?php

namespace App\Repository;

use App\Entity\ShortUrl;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ShortUrl>
 */
class ShortUrlRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShortUrl::class);
    }

    /**
     * @return list<ShortUrl>
     */
    public function findBatchAfterId(
        int $lastId,
        int $limit,
    ): array {
        return $this->createQueryBuilder('shortUrl')
            ->andWhere('shortUrl.id > :lastId')
            ->setParameter('lastId', $lastId)
            ->orderBy('shortUrl.id', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
