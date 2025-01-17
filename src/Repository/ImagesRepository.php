<?php

namespace App\Repository;

use App\Entity\Images;
use App\Entity\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Images>
 *
 * @method Images|null find($id, $lockMode = null, $lockVersion = null)
 * @method Images|null findOneBy(array $criteria, array $orderBy = null)
 * @method Images[]    findAll()
 * @method Images[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImagesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Images::class);
    }

    public function save(Images $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Images $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }  

    // Affichage des images pour tous les utilisateurs
    // Seules les images non bloquées (visibles)
    public function imagesPaginated(int $page, int $limit = 0): array
    {
        $limit = abs($limit);

        $result = [];

        $query = $this->getEntityManager()->createQueryBuilder()
            ->select('image')
            ->from('App\Entity\Images', 'image')
            ->where('image.is_visible = 1')
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

    // Affichage de toutes les images réservé aux administrateurs
    // Affichages de toutes les images bloquées (avec badge indicatif) et des non-bloquées
    public function imagesPaginatedAll(int $page, int $limit = 0): array
    {
        $limit = abs($limit);

        $result = [];

        $query = $this->getEntityManager()->createQueryBuilder()
            ->select('image')
            ->from('App\Entity\Images', 'image')
            ->setMaxResults($limit)
            ->setFirstResult(($page * $limit) - $limit);

        $paginator = new Paginator($query);
        $data = $paginator->getQuery()->getResult();

        if (empty($data)) {
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

    // Affichage des images différent pour un utilisateur donné
    // Afichage des images non-bloquées et des images bloquées de l'utilisateur connecté avec badge indicatif
    public function imagesPaginatedUser(Users $user, int $page, int $limit = 0): array
    {
        $limit = abs($limit);

        $result = [];

        //$user = $this->getUser();

        $query = $this->getEntityManager()->createQueryBuilder()
            ->select('image')
            ->from('App\Entity\Images', 'image')
            ->where('image.is_visible = 1')
            ->innerJoin('App\Entity\Users', 'user')
            ->Where('user.id = image.user')
            ->setMaxResults($limit) 
            ->setFirstResult(($page * $limit) - $limit);

        $paginator = new Paginator($query);
        $data = $paginator->getQuery()->getResult();

        if (empty($data)) {
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

//    /**

//     * @return Images[] Returns an array of Images objects
//     */
//      $user = $this->getUser();
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Images
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
