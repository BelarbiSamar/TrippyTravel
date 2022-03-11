<?php

namespace App\Repository;

use App\Entity\Articlimages;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Articlimages|null find($id, $lockMode = null, $lockVersion = null)
 * @method Articlimages|null findOneBy(array $criteria, array $orderBy = null)
 * @method Articlimages[]    findAll()
 * @method Articlimages[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticlimagesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Articlimages::class);
    }

    // /**
    //  * @return Articlimages[] Returns an array of Articlimages objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Articlimages
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
