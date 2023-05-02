<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ArticleEntityRepository<Article>
 *
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function save(Article $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Article $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }



//    /**
//     * @return Article[] Returns an array of Article objects
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

//    public function findOneBySomeField($value): ?Article
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function countByCatLib($catlib)
    {
        return $this->createQueryBuilder('r')
            ->select('COUNT(r.catlib)')
            ->where('r.catlib = :catlib')
            ->setParameter('catlib', $catlib)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findAllSorted(): array
    {
        $queryBuilder = $this->createQueryBuilder('cl')
            ->orderBy('cl.artprix', 'ASC');

        return $queryBuilder->getQuery()->getResult();
    }
    public function findAllSorted1(): array
    {
        $queryBuilder = $this->createQueryBuilder('cl')
            ->orderBy('cl.artprix', 'DESC');

        return $queryBuilder->getQuery()->getResult();
    }

    public function advancedSearch($query, $artid, $artlib, $catlib)
    {
        $qb = $this->createQueryBuilder('c');

        if ($query) {
            $qb->andWhere($qb->expr()->orX(
                $qb->expr()->like('c.artid', ':query'),
                $qb->expr()->like('c.artlib', ':query'),
                $qb->expr()->like('c.catlib', ':query'),

            ))
                ->setParameter('query', '%' . $query . '%');
        }

        if ($artid) {
            $qb->andWhere('c.artid = :artid')
                ->setParameter('artid', $artid);
        }

        if ($artlib) {
            $qb->andWhere('c.artlib = :artlib')
                ->setParameter('artlib', $artlib);
        }

        if ($catlib) {
            $qb->andWhere('c.catlib = :catlib')
                ->setParameter('catlib', $catlib);
        }


    }
}