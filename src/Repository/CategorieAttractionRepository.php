<?php

namespace App\Repository;

use App\Entity\CategorieAttraction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CategorieAttraction|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategorieAttraction|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategorieAttraction[]    findAll()
 * @method CategorieAttraction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategorieAttractionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategorieAttraction::class);
    }

    // /**
    //  * @return CategorieAttraction[] Returns an array of CategorieAttraction objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CategorieAttraction
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findEntitiesByLibelle($libelle){
        return $this->getEntityManager()
            ->createQuery(
                'SELECT c
                FROM App\Entity\CategorieAttraction c
                WHERE c.libelle LIKE :libelle'
            )
            ->setParameter('libelle', '%'.$libelle.'%')
            ->getResult();
    }
}
