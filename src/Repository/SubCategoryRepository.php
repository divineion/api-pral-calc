<?php

namespace App\Repository;

use App\Entity\SubCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SubCategory>
 */
class SubCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SubCategory::class);
    }

    //    /**
    //     * @return SubCategory[] Returns an array of SubCategory objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?SubCategory
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findOneById($value): ?SubCategory
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.id = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    public function findOneByName($value): ?subCategory
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.name = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    public function findByCategory($value): array
    {
        return $this->createQueryBuilder('s')
            ->select('s.name', 's.id')
            ->andWhere('s.category = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findAll(): array
    {
        $qb = $this->createQueryBuilder('s')
            ->select('s.name', 's.id');

        return $qb->getQuery()->getResult();
    }
}
