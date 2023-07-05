<?php

namespace App\Repository;

use App\Entity\Goods;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;

/**
 * @extends ServiceEntityRepository<Goods>
 *
 * @method Goods|null find($id, $lockMode = null, $lockVersion = null)
 * @method Goods|null findOneBy(array $criteria, array $orderBy = null)
 * @method Goods[]    findAll()
 * @method Goods[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GoodsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Goods::class);
    }

    public function save(Goods $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Goods $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getGoodsPaginator($page = 1, $perPage = 10, ?float $minPrice = null, ?float $maxPrice = null, ?string $sort = null, ?string $type = null, ?string $gender = null): Pagerfanta
    {
        $queryBuilder = $this->createQueryBuilder('g');

        if ($sort === 'price-asc') {
            $queryBuilder->orderBy('g.price', 'ASC');
        } elseif ($sort === 'price-desc') {
            $queryBuilder->orderBy('g.price', 'DESC');
        } else {
            $queryBuilder->orderBy('g.id', 'DESC');
        }

        if ($minPrice !== null && $maxPrice !== null) {
            $queryBuilder
                ->andWhere('g.price >= :minPrice')
                ->andWhere('g.price <= :maxPrice')
                ->setParameter('minPrice', $minPrice)
                ->setParameter('maxPrice', $maxPrice);
        } elseif ($minPrice !== null) {
            $queryBuilder
                ->andWhere('g.price >= :minPrice')
                ->setParameter('minPrice', $minPrice);
        } elseif ($maxPrice !== null) {
            $queryBuilder
                ->andWhere('g.price <= :maxPrice')
                ->setParameter('maxPrice', $maxPrice);
        }

        if ($type !== null) {
            $queryBuilder
                ->andWhere('g.Type = :type')
                ->setParameter('type', $type);
        }

        if ($gender !== null) {
            $queryBuilder
                ->andWhere('g.Gender = :gender')
                ->setParameter('gender', $gender);
        }

        $query = $queryBuilder->getQuery();

        $adapter = new QueryAdapter($query);
        $paginator = new Pagerfanta($adapter);

        $paginator->setCurrentPage($page);
        $paginator->setMaxPerPage($perPage);

        return $paginator;
    }



    public function searchByKeyword($keyword, $page = 1, $perPage = 10, ?float $minPrice = null, ?float $maxPrice = null, ?string $sort = null, ?string $gender = null)
    {
        $queryBuilder = $this->createQueryBuilder('g')
            ->where('g.name LIKE :keyword')
            ->setParameter('keyword', '%' . $keyword . '%');

        if ($sort === 'price-asc') {
            $queryBuilder->orderBy('g.price', 'ASC');
        } elseif ($sort === 'price-desc') {
            $queryBuilder->orderBy('g.price', 'DESC');
        } else {
            $queryBuilder->orderBy('g.id', 'DESC');
        }

        if ($minPrice !== null && $maxPrice !== null) {
            $queryBuilder
                ->andWhere('g.price >= :minPrice')
                ->andWhere('g.price <= :maxPrice')
                ->setParameter('minPrice', $minPrice)
                ->setParameter('maxPrice', $maxPrice);
        } elseif ($minPrice !== null) {
            $queryBuilder
                ->andWhere('g.price >= :minPrice')
                ->setParameter('minPrice', $minPrice);
        } elseif ($maxPrice !== null) {
            $queryBuilder
                ->andWhere('g.price <= :maxPrice')
                ->setParameter('maxPrice', $maxPrice);
        }

        if ($gender !== null) {
            $queryBuilder
                ->andWhere('g.Gender = :gender')
                ->setParameter('gender', $gender);
        }

        $query = $queryBuilder->getQuery();

        $adapter = new QueryAdapter($query);
        $paginator = new Pagerfanta($adapter);

        $paginator->setCurrentPage($page);
        $paginator->setMaxPerPage($perPage);

        return $paginator;
    }

//    /**
//     * @return Goods[] Returns an array of Goods objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('g.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Goods
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}