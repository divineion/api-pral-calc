<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Event>
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    //    /**
    //     * @return Event[] Returns an array of Event objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('e.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

        public function findOneById($value): ?Event
        {
            return $this->createQueryBuilder('e')
                ->andWhere('e.id = :val')
                ->setParameter('val', $value)
                ->getQuery()
                ->getOneOrNullResult()
            ;
        }
    public function findAllEventsByUser($value, $startDate, $endDate)
    {
        $qb = $this->createQueryBuilder('e')
            ->where('e.user = :val')
            ->setParameter('val', $value)
            ->andWhere('e.date >= :startDate')
            ->setParameter('startDate', $startDate)
            ->andWhere('e.date < :endDate')
            ->setParameter('endDate', $endDate);
        return $qb->getQuery()->getResult();
    }
}
