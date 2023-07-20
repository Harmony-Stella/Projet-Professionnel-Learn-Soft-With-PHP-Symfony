<?php

namespace App\Repository;

use App\Entity\EleveSujet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;

/**
 * @extends ServiceEntityRepository<EleveSujet>
 *
 * @method EleveSujet|null find($id, $lockMode = null, $lockVersion = null)
 * @method EleveSujet|null findOneBy(array $criteria, array $orderBy = null)
 * @method EleveSujet[]    findAll()
 * @method EleveSujet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EleveSujetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EleveSujet::class);
    }

    public function add(EleveSujet $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(EleveSujet $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function CountReponduSujets($idSujet)
    {
        return $this->createQueryBuilder('s')
             ->select("COUNT(s.id)")
             ->where("s.sujet=:id")
             ->andWhere('s.etat=1')
             ->setParameter(':id',$idSujet)
             ->getQuery()
             ->getSingleScalarResult()
             ;
       
    }

    public function CountTrouverSujets($idSujet)
    {
        return $this->createQueryBuilder('s')
             ->select("COUNT(s.id)")
             ->where("s.sujet=:id")
             ->andWhere('s.etatReponse=1 or s.etatReponse=2')
             ->setParameter(':id',$idSujet)
             ->getQuery()
             ->getSingleScalarResult()
             ;
       
    }

    public function EvaluationSujets($LesSujets)
    {
        foreach ($LesSujets as $key => $value) {
        $query = $this->createQueryBuilder('s');
        
        $query = $query
            
             ->where('s.sujet=:id')
             ->setParameter('id',$value->getId());
            
         }
         return $query ->getQuery()
                       ->getResult();
       
    }

    public function SujetEvaluation($idEvaluation,$idEleve)
    {
        return $this->createQueryBuilder('s')
             ->where("s.evaluation=:idEvaluation")
             ->andWhere('s.eleve=:idEleve')
             ->setParameter(':idEvaluation',$idEvaluation)
             ->setParameter(':idEleve',$idEleve)
             ->getQuery()
             ->getResult()
             ;
       
    }

//    /**
//     * @return EleveSujet[] Returns an array of EleveSujet objects
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

//    public function findOneBySomeField($value): ?EleveSujet
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
