<?php

namespace App\Repository;

use App\Entity\Review;
use App\Entity\UserRating;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Review|null find($id, $lockMode = null, $lockVersion = null)
 * @method Review|null findOneBy(array $criteria, array $orderBy = null)
 * @method Review[]    findAll()
 * @method Review[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReviewRepository extends ServiceEntityRepository
{
    private const MAX_REVIEWS_ITEMS = 3;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Review::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Review $entity, bool $flush = true): void
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
    public function remove(Review $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function update(Review $entity, bool $flush = true): void
    {
    }

    /**
     * @return Review[]
     */
    public function getAll(): array
    {
        $query = $this->_em->createQuery(
            'SELECT r,c FROM App\Entity\Review r JOIN r.category c ORDER BY r.id ASC'
        );
        return $query->getResult();
    }

    /**
     * @return Review[]
     */
    public function getUserReviews(int $author): array
    {
        $query = $this->_em->createQuery(
            'SELECT r,c FROM App\Entity\Review r 
                JOIN r.category c
                WHERE r.author = ?1
                ORDER BY r.id ASC'
        );
        $query->setParameter(1, $author);
        return $query->getResult();
    }

    /**
     * @return Review[]
     */
    public function getRecentlyAdded()
    {
        $query = $this->_em->createQuery(
            'SELECT r,c,a,i,t,ur FROM App\Entity\Review r
                 INNER JOIN r.category c
                 INNER JOIN r.author a
                 LEFT JOIN r.images i
                 LEFT JOIN r.tags t
                 LEFT JOIN r.usersRating ur
                 ORDER BY r.createdAt DESC'
        );
//        $query->setMaxResults(self::MAX_REVIEWS_ITEMS);
        return $query->getResult(AbstractQuery::HYDRATE_OBJECT);
    }

    // /**
    //  * @return Review[] Returns an array of Review objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Review
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
