<?php

namespace App\Repository;

use App\Entity\EleveEvaluation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EleveEvaluation>
 *
 * @method EleveEvaluation|null find($id, $lockMode = null, $lockVersion = null)
 * @method EleveEvaluation|null findOneBy(array $criteria, array $orderBy = null)
 * @method EleveEvaluation[]    findAll()
 * @method EleveEvaluation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EleveEvaluationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EleveEvaluation::class);
    }

    public function add(EleveEvaluation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(EleveEvaluation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function UneEleveEvaluation($idEvaluation,$idEleve)
    {
       return $this->createQueryBuilder('e')
            ->where('e.evaluation = :id')
            ->andWhere('e.eleve = :idEleve')
            ->setParameter('id',$idEvaluation)
            ->setParameter('idEleve',$idEleve)
            ->getQuery()
            ->getResult()
        ;
     }

    public function EleveEvaluation($idEleve)
    {
       return $this->createQueryBuilder('e')
            ->andWhere('e.eleve = :idEleve')
            ->setParameter('idEleve',$idEleve)
            ->getQuery()
            ->getResult()
        ;
     }
     public function EleveEvaluationParent()
    {
       return $this->createQueryBuilder('e')
            ->getQuery()
            ->getResult()
        ;
     }

     public function SelectEvaluation($idEvaluation)
    {
       return $this->createQueryBuilder('e')
            //->where('e.eleve=:id')
            ->andWhere('e.evaluation = :idEvaluation')
            ->setParameter('idEvaluation',$idEvaluation)
            ->getQuery()
            ->getResult()
        ;
     }

      public function NoteEleveEvaluation($idEleve)
    {
       return $this->createQueryBuilder('e')
            //->where('e.eleve=:id')
            ->andWhere('e.eleve = :idEleve')
            ->setParameter('idEleve',$idEleve)
            ->getQuery()
            ->getResult()
        ;
     }

    public function CountEleveEvaluation($idEleve)
    {
       return $this->createQueryBuilder('e')
            ->select("COUNT(e.id)")
             ->where("e.eleve=:idEleve")
             ->andWhere("e.note>=0")
             ->setParameter(':idEleve',$idEleve)
             ->getQuery()
             ->getSingleScalarResult()
        ;
     }
    

//    /**
//     * @return EleveEvaluation[] Returns an array of EleveEvaluation objects
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

//    public function findOneBySomeField($value): ?EleveEvaluation
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
