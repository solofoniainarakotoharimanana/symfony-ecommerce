<?php

namespace App\Repository;

use App\Class\SearchProduct;
use App\Entity\Products;
use App\Entity\SubCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Products>
 */
class ProductsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Products::class);
    }

    //    /**
    //     * @return Products[] Returns an array of Products objects
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

    //    public function findOneBySomeField($value): ?Products
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findBySearch(SearchProduct $searchProduct)
    {
        $query = $this->createQueryBuilder('p')
                 ->join('p.subCategories', 's')
                 ->join('s.category', 'c')
                 ->addOrderBy('p.createdAt', 'DESC');
        if (!empty($searchProduct->name)) {
            $query = $query->andWhere('p.name LIKE :name')
                     ->setParameter('name', '%'.$searchProduct->name.'%');
        }

        if (count($searchProduct->categories) > 0) {
            $query = $query->andWhere('c.id IN (:categories)')
                     ->setParameter('categories', $searchProduct->categories);
        }

        $query = $query->getQuery()
                ->getResult();
                
        return $query;
    }
}