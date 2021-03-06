<?php

namespace App\Repository;

use App\Entity\DataEntry;
use App\Entity\Location;
use App\Entity\Topic;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method DataEntry|null find($id, $lockMode = null, $lockVersion = null)
 * @method DataEntry|null findOneBy(array $criteria, array $orderBy = null)
 * @method DataEntry[]    findAll()
 * @method DataEntry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DataEntryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DataEntry::class);
    }

    /**
     * @param Location $location
     * @param Topic $topic
     * @return int[]
     */
    public function findYearsFrom(Location $location, Topic $topic): array
    {
        $years = $this->createQueryBuilder('d')
            ->select('d.yearFrom as year')
            ->andWhere('d.location = :location')
            ->andWhere('d.topic = :topic')
            ->setParameter('location', $location)
            ->setParameter('topic', $topic)
            ->addGroupBy('d.yearFrom')
            ->orderBy('d.yearFrom')
            ->getQuery()
            ->getResult();

        foreach($years as &$entry){
            $entry = $entry['year'];
        }

        return $years;
    }

    /**
     * @param Location $location
     * @param Topic $topic
     * @return int[]
     */
    public function findYearsTo(Location $location, Topic $topic): array
    {
        $years = $this->createQueryBuilder('d')
            ->select('d.yearTo as year')
            ->andWhere('d.location = :location')
            ->andWhere('d.topic = :topic')
            ->setParameter('location', $location)
            ->setParameter('topic', $topic)
            ->addGroupBy('d.yearTo')
            ->orderBy('d.yearTo')
            ->getQuery()
            ->getResult();

        foreach($years as &$entry){
            $entry = $entry['year'];
        }

        return $years;
    }

    /**
     * @param Location|null $location
     * @param Topic|null $topic
     * @param int|null $yearFrom
     * @param int|null $yearTo
     * @param int|null $offset
     * @param int|null $limit
     * @return int
     */
    public function findByLocationTopicYearTotal(?Location $location = null, ?Topic $topic = null, ?int $yearFrom = null, ?int $yearTo = null): int
    {
        return $this->createLocationTopicYearQuery($location, $topic, $yearFrom, $yearTo)
            ->select('COUNT(d.id) AS total')
            ->getQuery()
            ->getOneOrNullResult()['total'];
    }

    /**
     * @param Location|null $location
     * @param Topic|null $topic
     * @param int|null $yearFrom
     * @param int|null $yearTo
     * @param int|null $offset
     * @param int|null $limit
     * @return DataEntry[]
     */
    public function findByLocationTopicYear(?Location $location = null, ?Topic $topic = null, ?int $yearFrom = null, ?int $yearTo = null, ?int $offset = null, ?int $limit = null): array
    {
        $query = $this->createLocationTopicYearQuery($location, $topic, $yearFrom, $yearTo);



        if(!is_null($offset)){
            $query->setFirstResult($offset);
        }

        if(!is_null($limit)){
            $query->setMaxResults($limit);
        }

        return $query->getQuery()
        ->getResult();
    }


    /**
     * @param Location|null $location
     * @param Topic|null $topic
     * @param int|null $yearFrom
     * @param int|null $yearTo
     * @return QueryBuilder
     */
    protected function createLocationTopicYearQuery(?Location $location, ?Topic $topic, ?int $yearFrom, ?int $yearTo): QueryBuilder
    {
        if(is_null($yearFrom)){
            $yearFrom = 0;
        }

        if(is_null($yearTo)){
            $yearTo = 9999;
        }

        $query = $this->createQueryBuilder('d');

        if(!empty($location)) {
            $query->andWhere('d.location = :location')
                ->setParameter('location', $location);
        }

        if(!empty($topic)) {
            $query->andWhere('d.topic = :topic')
                ->setParameter('topic', $topic);
        }

        return $query->andWhere('d.yearFrom >= :yearFrom AND d.yearTo <= :yearTo')
        ->setParameter('yearFrom', $yearFrom)
        ->setParameter('yearTo', $yearTo)
        ->addOrderBy('d.yearFrom')
        ->addOrderBy('d.yearTo')
        ->addOrderBy('d.id');
    }

    // /**
    //  * @return DataEntry[] Returns an array of DataEntry objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DataEntry
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
