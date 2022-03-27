<?php

namespace App\Repository;

use App\Entity\UserRating;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserRating|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserRating|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserRating[]    findAll()
 * @method UserRating[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRatingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserRating::class);
    }

    public function add(UserRating $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function remove(UserRating $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function update(UserRating $entity)
    {
        $dql = $this->_em->createQuery(
            'UPDATE App\Entity\UserRating ur
                SET ur.value = ?1
                WHERE ur.userId = ?2 and ur.review = ?3');
        $dql->setParameter(1, $entity->getValue());
        $dql->setParameter(2, $entity->getUserId());
        $dql->setParameter(3, $entity->getReview());
        return $dql->getResult();
    }

    public function getAvgRatingByReviewId($reviewId)
    {
        $dql = $this->_em->createQuery(
            'SELECT AVG(ur.value) FROM App\Entity\UserRating ur
                WHERE ur.review = ?1'
        );
        $dql->setParameter(1, $reviewId);
        return $dql->getResult();
    }

    // /**
    //  * @return UserRating[] Returns an array of UserRating objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserRating
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
