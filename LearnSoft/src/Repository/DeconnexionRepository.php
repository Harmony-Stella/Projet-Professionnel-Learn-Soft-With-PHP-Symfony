<?php

namespace App\Repository;

use App\Entity\Deconnexion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Deconnexion>
 *
 * @method Deconnexion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Deconnexion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Deconnexion[]    findAll()
 * @method Deconnexion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DeconnexionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Deconnexion::class);
    }

    public function add(Deconnexion $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Deconnexion $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function Login($idEleve)
    {
       return $this->createQueryBuilder('d')
            ->andWhere('d.eleve = :idEleve')
            ->setParameter('idEleve',$idEleve)
            ->orderBy('d.id', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
     }
     public function LoginParent($idParent)
    {
       return $this->createQueryBuilder('d')
       ->leftJoin('d.eleve','e')
        ->where('e.parents= :idParent')
            //->andWhere('d.eleve = :idEleve')
            ->setParameter('idParent',$idParent)
            ->orderBy('d.id', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
     }

      public function LoginOne($idEleve)
    {
       return $this->createQueryBuilder('d')
            ->select('d.status')
            ->where('d.eleve= :idEleve')
            //->andWhere('d.eleve = :idEleve')
            ->setParameter('idEleve',$idEleve)
            ->orderBy('d.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleScalarResult()
        ;
     }

//    /**
//     * @return Deconnexion[] Returns an array of Deconnexion objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Deconnexion
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
