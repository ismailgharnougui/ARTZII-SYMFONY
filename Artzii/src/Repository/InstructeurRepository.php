<?php

namespace App\Repository;

use App\Entity\Instructeur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Instructeur>
 *
 * @method Instructeur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Instructeur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Instructeur[]    findAll()
 * @method Instructeur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InstructeurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Instructeur::class);
    }

    public function save(Instructeur $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Instructeur $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Instructeur[] Returns an array of Instructeur objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Instructeur
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
