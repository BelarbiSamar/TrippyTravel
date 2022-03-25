<?php

namespace App\Repository;

use App\Entity\Excursioncategorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Excursioncategorie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Excursioncategorie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Excursioncategorie[]    findAll()
 * @method Excursioncategorie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExcursioncategorieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Excursioncategorie::class);
    }

    // /**
    //  * @return Excursioncategorie[] Returns an array of Excursioncategorie objects
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
    public function findOneBySomeField($value): ?Excursioncategorie
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
