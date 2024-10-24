<?php

namespace App\Repository;

use App\Entity\Alim;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Alim>
 */
class AlimRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Alim::class);
    }

        /**
         * @return Alim[] Returns an array of Alim objects
         */
        public function findAllFoodLabels(): array
        {
            $qb = $this->createQueryBuilder('a')
                ->select('a.food_label', 'a.id', 'a.pral_index');

            return $qb->getQuery()->getResult();
        }

        public function findOneById($value): ?Alim
        {
            $qb = $this->createQueryBuilder('a')
                ->select('a')
                ->andWhere('a.id = :val')
                ->setParameter('val', $value);

            return $qb->getQuery()->getOneOrNullResult();
        }
}
