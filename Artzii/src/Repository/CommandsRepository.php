<?php

namespace App\Repository;

use App\Entity\Commands;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Commands>
 *
 * @method Commands|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commands|null findOneBy(array $criteria, array $orderBy = null)
 * @method Commands[]    findAll()
 * @method Commands[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommandsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commands::class);
    }

    public function save(Commands $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Commands $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getTotalPriceOfCurrentMonth(): float
{
    $startDate = new \DateTime('first day of this month');
    $endDate = new \DateTime('last day of this month');

    $qb = $this->createQueryBuilder('c')
        ->select('SUM(c.coutTotale) as total')
        ->where('c.dateCommande BETWEEN :startDate AND :endDate')
        ->setParameter('startDate', $startDate)
        ->setParameter('endDate', $endDate);

    $result = $qb->getQuery()->getSingleResult();

    return $result['total'] ?? 0.0;
}

//    /**
//     * @return Commands[] Returns an array of Commands objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Commands
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
