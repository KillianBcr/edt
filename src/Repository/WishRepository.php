<?php

namespace App\Repository;

use App\Entity\Wish;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Wish>
 *
 * @method Wish|null find($id, $lockMode = null, $lockVersion = null)
 * @method Wish|null findOneBy(array $criteria, array $orderBy = null)
 * @method Wish[]    findAll()
 * @method Wish[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WishRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Wish::class);
    }

    public function findAllWishesGroupedByYear($selectedAcademicYear): array
    {
        $query = $this->createQueryBuilder('w')
            ->select('w', 's', 'y.academicYear', 'u.firstname', 'u.lastname')
            ->join('w.subjectId', 's')
            ->join('s.academicYear', 'y')
            ->join('w.wishUser', 'u');

        if ($selectedAcademicYear) {
            $query->andWhere('y.academicYear = :selectedAcademicYear')
                ->setParameter('selectedAcademicYear', $selectedAcademicYear);
        }

        $result = $query
            ->orderBy('y.academicYear', 'ASC')
            ->getQuery()
            ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

        return $result;
    }

    public function findDistinctAcademicYears(): array
    {
        $result = $this->createQueryBuilder('w')
            ->select('DISTINCT y.academicYear')
            ->join('w.subjectId', 's')
            ->join('s.academicYear', 'y')
            ->orderBy('y.academicYear', 'ASC')
            ->getQuery()
            ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

        return array_column($result, 'academicYear');
    }


    //    /**
    //     * @return Wish[] Returns an array of Wish objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('w')
    //            ->andWhere('w.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('w.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Wish
    //    {
    //        return $this->createQueryBuilder('w')
    //            ->andWhere('w.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
