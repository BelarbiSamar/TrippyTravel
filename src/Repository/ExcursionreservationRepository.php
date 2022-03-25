<?php

namespace App\Repository;

use App\Entity\Excursionreservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Excursionreservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Excursionreservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Excursionreservation[]    findAll()
 * @method Excursionreservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExcursionreservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Excursionreservation::class);
    }

    // /**
    //  * @return Excursionreservation[] Returns an array of Excursionreservation objects
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
    public function findOneBySomeField($value): ?Excursionreservation
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
