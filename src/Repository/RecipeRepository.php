<?php

namespace App\Repository;

use App\Entity\Recipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Recipe>
 */
class RecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipe::class);
    }

    //    /**
    //     * @return Recipe[] Returns an array of Recipe objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Recipe
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findRecipeById($value): ?Recipe
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.id = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findRecipesByUserId($value): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.user = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findLatestRecipesByUserId($value): array
    {
        $qb = $this->createQueryBuilder('r')
            ->andWhere('r.user = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'DESC')
            ->setMaxResults(1);

        return $qb->getQuery()->getResult();
    }

    public function findAll(): array
    {
        $qb = $this->createQueryBuilder('r')
            ->select('r.id', 'r.title', 's.name as subcategory', 'c.name as category')
            ->leftJoin('r.category', 'c')
            ->leftJoin('r.subCategory', 's');
        return $qb->getQuery()->getResult();
    }

    public function findAllAlimentsInRecipe($value): array
    {
        $qb = $this->createQueryBuilder('r')
            ->select('a.food_label', 'a.id')
            ->innerJoin('r.aliments', 'a')
            ->where('r.id = :val')
            ->setParameter('val', $value);

        return $qb->getQuery()->getResult();
    }

    public function findLatest()
    {
        $qb = $this->createQueryBuilder('r')
            ->select('r.id', 'r.title', 's.name as subcategory', 'c.name as category', 'r.pralIndex')
            ->leftJoin('r.subCategory', 's')
            ->leftJoin('r.category', 'c')
            ->orderBy('r.id', 'DESC')
            ->setMaxResults(3);

        return $qb->getQuery()->getResult();
    }

}
