<?php

namespace App\Repository;

use App\Entity\Location;
use App\Entity\Topic;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Topic>
 */
class TopicRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Topic::class);
    }

    public function findRoot(): Topic
    {
        return $this->createQueryBuilder('t')
            ->where('t.parent IS NULL')
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * @return Topic[]
     */
    public function findWithDataEntries(?Location $location = null): array
    {
        if (null !== $location) {
            return $this->createQueryBuilder('t')
                ->innerJoin('t.dataEntries', 'd')
                ->addSelect('t')
                ->andWhere('d.location = :location')
                ->setParameter('location', $location)
                ->addGroupBy('t')
                ->getQuery()
                ->getResult()
            ;
        }

        return $this->createQueryBuilder('t')
            ->innerJoin('t.dataEntries', 'd')
            ->addSelect('t')
            ->addGroupBy('t')
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return Topic[] Returns an array of Topic objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Topic
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
