<?php

namespace App\Repository;

use App\Entity\ProgressionJour;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProgressionJour>
 *
 * @method ProgressionJour|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProgressionJour|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProgressionJour[]    findAll()
 * @method ProgressionJour[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProgressionJourRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProgressionJour::class);
    }

    public function add(ProgressionJour $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ProgressionJour $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

     public function Progression($endAt)
      {
       return $this->createQueryBuilder('p')
            ->where('p.dateDuJour = :endAt')
            ->setParameter('endAt',$endAt)
            ->getQuery()
            ->getResult()
        ;
     }

      public function Performance($idEleve)
      {
       return $this->createQueryBuilder('p')
            ->where('p.eleve = :idEleve')
            ->setParameter('idEleve',$idEleve)
            ->getQuery()
            ->getResult()
        ;
     }

//    /**
//     * @return ProgressionJour[] Returns an array of ProgressionJour objects
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

//    public function findOneBySomeField($value): ?ProgressionJour
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
