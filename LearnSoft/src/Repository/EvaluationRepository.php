<?php

namespace App\Repository;

use App\Entity\Evaluation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Evaluation>
 *
 * @method Evaluation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Evaluation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Evaluation[]    findAll()
 * @method Evaluation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EvaluationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Evaluation::class);
    }

    public function add(Evaluation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Evaluation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function LesEvaluations($idParent)
      {
       return $this->createQueryBuilder('e')
            ->where('e.parents = :id')
            ->setParameter('id',$idParent)
            ->orderBy('e.id', 'DESC')
            ->getQuery()
            ->getResult()
        ;
     }

    public function UneEvaluation($idEvaluation)
      {
       return $this->createQueryBuilder('e')
            ->where('e.id = :id')
            ->setParameter('id',$idEvaluation)
            ->getQuery()
            ->getResult()
        ;
     }

     public function ClasseEvaluation($idClasse)
    {
       return $this->createQueryBuilder('e')
            ->where('e.classe = :id')
            ->orderBy('e.id','DESC')
            ->setParameter('id',$idClasse)
            ->getQuery()
            ->getResult()
        ;
     }

      public function CountEvaluation($idClasse)
    {
       return $this->createQueryBuilder('e')
            ->select("COUNT(e.id)")
             ->where("e.classe=:idClasse")
             ->setParameter(':idClasse',$idClasse)
             ->getQuery()
             ->getSingleScalarResult()
        ;
     }

       public function CountEvaluationP()
    {
       return $this->createQueryBuilder('e')
            ->select("COUNT(e.id)")
             ->getQuery()
             ->getSingleScalarResult()
        ;
     }
     

//    /**
//     * @return Evaluation[] Returns an array of Evaluation objects
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

//    public function findOneBySomeField($value): ?Evaluation
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
