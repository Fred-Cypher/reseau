<?php

namespace App\Repository;

use App\Entity\Articles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Articles>
 *
 * @method Articles|null find($id, $lockMode = null, $lockVersion = null)
 * @method Articles|null findOneBy(array $criteria, array $orderBy = null)
 * @method Articles[]    findAll()
 * @method Articles[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticlesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Articles::class);
    }

    public function save(Articles $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Articles $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function articlesPaginated(int $page, int $limit = 0): array
    {
        $limit = abs($limit);

        $result = [];

        $query = $this->getEntityManager()->createQueryBuilder()
            ->select('article')
            ->from('App\Entity\Articles', 'article')
            //->where('article.is_visible = 1')
            ->setMaxResults($limit)
            ->setFirstResult(($page * $limit) - $limit);

        $paginator = new Paginator($query);
        $data = $paginator->getQuery()->getResult();

        if(empty($data)){
            return $result;
        }

        //Calcul du nombre de pages
        $pages = ceil($paginator->count() / $limit);

        //Remplissage tableau
        $result['data'] = $data;
        $result['pages'] = $pages;
        $result['page'] = $page;
        $result['limit'] = $limit;

        return $result;
    }

    /*public function articlesVisibility($isVisible): bool
    {

        return $this->createQueryBuilder('article')
            ->andwhere('is_visible = :visible')
            ->setParameter('visible', $isVisible)
            ->getQuery()
            ->getResult();
    }*/

    //    /**
    //     * @return Articles[] Returns an array of Articles objects
    //     */

    /*public function findAll()
    {
        return $this->findBy(array());
    }*/

    /*public function findAllArticles(): array{

        $result=[];

        $query = $this->getEntityManager()->createQueryBuilder()
        ->select('t')
        ->

        return $result;
    }*/

    /**
     * $article = new Articles;

     *      $articleArray = $article->findBy($id);

     *      if($articleArray){
     *         $article = $article->hydrate();
            
     *         $article->setVisible($article->getVisible() ? 0 : 1);

     *         $article->update();
     *    }
     */

//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Articles
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
