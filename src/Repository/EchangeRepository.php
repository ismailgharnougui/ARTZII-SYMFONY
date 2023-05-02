<?php

namespace App\Repository;

use App\Entity\Echange;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Echange|null find($id, $lockMode = null, $lockVersion = null)
 * @method Echange|null findOneBy(array $criteria, array $orderBy = null)
 * @method Echange[]    findAll()
 * @method Echange[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EchangeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Echange::class);
    }

    /**
     * @return Echange[]
     */
    public function findEnAttente(): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.statut = :en_attente')
            ->setParameter('en_attente', 'en attente')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param int $userId
     * @return Echange[]
     */
    public function findEchangesByLivreur(int $userId): array
    {
        return $this->createQueryBuilder('e')
            ->leftJoin('e.livreur', 'l')
            ->andWhere('l.id = :user_id')
            ->setParameter('user_id', $userId)
            ->getQuery()
            ->getResult();
    }

    // add other custom methods as needed
}
