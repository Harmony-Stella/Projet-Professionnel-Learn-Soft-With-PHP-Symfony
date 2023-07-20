<?php

namespace App\Repository;

use App\Entity\EmploiTemps;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EmploiTemps>
 *
 * @method EmploiTemps|null find($id, $lockMode = null, $lockVersion = null)
 * @method EmploiTemps|null findOneBy(array $criteria, array $orderBy = null)
 * @method EmploiTemps[]    findAll()
 * @method EmploiTemps[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmploiTempsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EmploiTemps::class);
    }

    public function add(EmploiTemps $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(EmploiTemps $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return EmploiTemps[] Returns an array of EmploiTemps objects
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

//    public function findOneBySomeField($value): ?EmploiTemps
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
