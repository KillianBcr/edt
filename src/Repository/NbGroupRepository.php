<?php

namespace App\Repository;

use App\Entity\NbGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<NbGroup>
 *
 * @method NbGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method NbGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method NbGroup[]    findAll()
 * @method NbGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NbGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NbGroup::class);
    }

    //    /**
    //     * @return NbGroup[] Returns an array of NbGroup objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('n')
    //            ->andWhere('n.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('n.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?NbGroup
    //    {
    //        return $this->createQueryBuilder('n')
    //            ->andWhere('n.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
