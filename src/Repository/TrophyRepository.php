<?php

namespace App\Repository;

use App\Entity\Trophy;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;

/**
 * @extends ServiceEntityRepository<Trophy>
 */
class TrophyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trophy::class);
    }

    //    /**
    //     * @return Trophy[] Returns an array of Trophy objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Trophy
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function userHasTrophy(User $user, string $trophyName): bool
    {
        return $this->createQueryBuilder('t')
                ->andWhere('t.user = :user')
                ->andWhere('t.name = :name')
                ->setParameter('user', $user)
                ->setParameter('name', $trophyName)
                ->getQuery()
                ->getResult() !== [];
    }

}
