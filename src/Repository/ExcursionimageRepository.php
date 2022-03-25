<?php

namespace App\Repository;

use App\Entity\Excursionimage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Excursionimage|null find($id, $lockMode = null, $lockVersion = null)
 * @method Excursionimage|null findOneBy(array $criteria, array $orderBy = null)
 * @method Excursionimage[]    findAll()
 * @method Excursionimage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExcursionimageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Excursionimage::class);
    }

    // /**
    //  * @return Excursionimage[] Returns an array of Excursionimage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Excursionimage
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
