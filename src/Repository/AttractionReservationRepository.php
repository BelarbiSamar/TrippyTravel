<?php

namespace App\Repository;

use App\Entity\AttractionReservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AttractionReservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method AttractionReservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method AttractionReservation[]    findAll()
 * @method AttractionReservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AttractionReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AttractionReservation::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(AttractionReservation $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(AttractionReservation $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return AttractionReservation[] Returns an array of AttractionReservation objects
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
    public function findOneBySomeField($value): ?AttractionReservation
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
