<?php

namespace App\Repository;

use App\Entity\Propositions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Propositions>
 *
 * @method Propositions|null find($id, $lockMode = null, $lockVersion = null)
 * @method Propositions|null findOneBy(array $criteria, array $orderBy = null)
 * @method Propositions[]    findAll()
 * @method Propositions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PropositionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Propositions::class);
    }

    public function add(Propositions $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Propositions $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function CountPropositions($idSujet)
    {
        return $this->createQueryBuilder('p')
             ->select("COUNT(p.id)")
             ->where("p.reponseValide=1")
             ->andWhere("p.sujet=:id")
             ->setParameter(':id',$idSujet)
             ->getQuery()
             ->getSingleScalarResult()
             ;
       
    }

    public function verificationReponse($idProposition)
      {
       return $this->createQueryBuilder('p')
            ->where('p.id = :id')
            ->setParameter('id',$idProposition)
            ->getQuery()
            ->getResult()
        ;
     }

    public function LesPropositions($idSujet)
    {
       return $this->createQueryBuilder('p')
            ->where('p.sujet = :id')
            ->setParameter('id',$idSujet)
            ->getQuery()
            ->getResult()
        ;
     }

//    /**
//     * @return Propositions[] Returns an array of Propositions objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Propositions
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
