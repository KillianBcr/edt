<?php

namespace App\Repository;

use App\Entity\Semester;
use App\Entity\Subject;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Subject>
 *
 * @method Subject|null find($id, $lockMode = null, $lockVersion = null)
 * @method Subject|null findOneBy(array $criteria, array $orderBy = null)
 * @method Subject[]    findAll()
 * @method Subject[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Subject::class);
    }

    public function queryAll(): Query
    {
        return $this->createQueryBuilder('u')
            ->select('u')
            ->orderBy('u.id', 'ASC')
            ->getQuery();
    }

    public function search(string $term): array
    {
        return $this->createQueryBuilder('s')
            ->where('s.name LIKE :term')
            ->setParameter('term', '%'.$term.'%')
            ->getQuery()
            ->getResult();
    }

    public function findBySemester(Semester $semester): array
    {
        return $this->createQueryBuilder('s')
            ->where('s.semester = :semester')
            ->setParameter('semester', $semester)
            ->getQuery()
            ->getResult();
    }

    public function findSubjectsWithWishes()
    {
        return $this->createQueryBuilder('s')
            ->join('App\Entity\Wish', 'w', 'WITH', 'w.subjectId = s.id')
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Subject[] Returns an array of Subject objects
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

    //    public function findOneBySomeField($value): ?Subject
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
