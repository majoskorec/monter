<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Gallery;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\UnexpectedResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 * @extends ServiceEntityRepository<Gallery>
 */
final class GalleryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Gallery::class);
    }

    /**
     * @throws UnexpectedResultException
     */
    public function findByKeys(string $page, string $gallery): ?Gallery
    {
        $data = $this->createQueryBuilder('g')
            ->join('g.page', 'p')
            ->andWhere('g.urlKey = :gallery')
            ->andWhere('p.urlKey = :page')
            ->setParameter('gallery', $gallery)
            ->setParameter('page', $page)
            ->getQuery()
            ->getSingleResult();
        assert($data instanceof Gallery || $data === null);

        return $data;
    }
}
