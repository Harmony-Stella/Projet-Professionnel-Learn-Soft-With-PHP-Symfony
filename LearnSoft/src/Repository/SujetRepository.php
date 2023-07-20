<?php

namespace App\Repository;

use App\Entity\Sujet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Sujet>
 *
 * @method Sujet|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sujet|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sujet[]    findAll()
 * @method Sujet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SujetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sujet::class);
    }

    public function add(Sujet $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Sujet $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function LesSujets($idEvaluation)
      {
       return $this->createQueryBuilder('s')
            ->leftJoin('s.evaluation','e')
            ->where('s.evaluation = :id')
            ->setParameter('id',$idEvaluation)
            ->getQuery()
            ->getResult()
        ;
     }

     public function SujetsId($idEvaluation)
      {
       return $this->createQueryBuilder('s')
            ->select('s.id')
            ->where('s.evaluation = :id')
            ->setParameter('id',$idEvaluation)
            ->getQuery()
            ->getResult()
        ;
     }

     public function nombrePropositions($idSujet)
      {
       return $this->createQueryBuilder('s')
            ->where('s.id = :id')
            ->setParameter('id',$idSujet)
            ->getQuery()
            ->getResult()
        ;
     }

     public function CountSujets()
      {
       return $this->createQueryBuilder('s')
            ->getQuery()
            ->getResult()
        ;
     }

    public function CountAllSujets($idEvaluation)
    {
        return $this->createQueryBuilder('s')
             ->select("COUNT(s.id)")
             ->where("s.evaluation=:id")
             ->setParameter(':id',$idEvaluation)
             ->getQuery()
             ->getSingleScalarResult()
             ;
       
    }
    

//    /**
//     * @return Sujet[] Returns an array of Sujet objects
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

//    public function findOneBySomeField($value): ?Sujet
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
