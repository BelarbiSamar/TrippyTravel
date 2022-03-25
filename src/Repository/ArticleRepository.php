<?php

namespace App\Repository;

use App\Entity\Article;
use App\Data\SearchData;
use App\Entity\Category;
use ContainerPhvxabs\PaginatorInterface_82dac15;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;


/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    /**
     * @var PaginatorInterface
     */
    private $paginator;
    public function __construct(ManagerRegistry $registry,PaginatorInterface  $paginator)
    {
        parent::__construct($registry, Article::class);
        $this->paginator = $paginator;
    }

    /**
     * @param SearchData $search
     * @return PaginationInterface
     */

    public function findSearch(SearchData $search): PaginationInterface
    {

        $query = $this
            ->createQueryBuilder('p')
            ->select('c', 'p')
            ->join('p.id_category', 'c');

        if (!empty($search->q)) {
            $query = $query
                ->andWhere('p.title LIKE :q')
                ->setParameter('q', "%{$search->q}%");
        }
        if (!empty($search->categories)) {
            $query = $query
                ->andWhere('c.id IN (:categories)')
                ->setParameter('categories', $search->categories);
        }
        $query =$query->getQuery();
        return $this->paginator->paginate(
            $query,
            1,
            6
        );


    }

/*    /**
     * @return Article[]|NULL Returns an array of articles objects
     **/
    /*
     public function findByExampleField($value)
        {
            $query= $this->createQueryBuilder('r');
            $query->andWhere(
                $query->expr()->like('r.title',  ':val')
            )
                ->setParameter('val', '%'.$value.'%');
            return $query->getQuery()->getResult();
        }*/


    // /**
    //  * @return Article[] Returns an array of Article objects
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
    public function findOneBySomeField($value): ?Article
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function getCountPerCategory(){
        return ($qb = $this->createQueryBuilder('u'))
            ->select('count(u.id_category)','c.id')
            ->from(Category::class, 'c')
            ->where('u.id_category=c.id')
            ->groupBy('u.id_category')
            ->getQuery()->getResult();

    }
}
