<?php

namespace App\Repository;

use App\Entity\SubjectCode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SubjectCode>
 *
 * @method SubjectCode|null find($id, $lockMode = null, $lockVersion = null)
 * @method SubjectCode|null findOneBy(array $criteria, array $orderBy = null)
 * @method SubjectCode[]    findAll()
 * @method SubjectCode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubjectCodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SubjectCode::class);
    }

    public function findOrCreateByCode(string $code): SubjectCode
    {
        $subjectCode = $this->findOneBy(['code' => $code]);

        if (!$subjectCode) {
            $subjectCode = new SubjectCode();
            $subjectCode->setCode($code);
            $this->getEntityManager()->persist($subjectCode);
            $this->getEntityManager()->flush();
        }

        return $subjectCode;
    }

//    /**
//     * @return SubjectCode[] Returns an array of SubjectCode objects
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

//    public function findOneBySomeField($value): ?SubjectCode
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
