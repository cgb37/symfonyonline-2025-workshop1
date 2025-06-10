<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Comment>
 */
class CommentRepository extends ServiceEntityRepository
{
    public const COMMENTS_PER_PAGE = 5;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    /**
     * Returns a paginator for comments of a given conference.
     *
     * @param \App\Entity\Conference $conference
     * @param int $offset
     * @return array
     */
    public function getCommentPaginator(\App\Entity\Conference $conference, int $offset): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.conference = :conference')
            ->setParameter('conference', $conference)
            ->orderBy('c.createdAt', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults(self::COMMENTS_PER_PAGE)
            ->getQuery()
            ->getResult();
    }

    /**
     * Returns the total number of comments for a conference.
     */
    public function countByConference(\App\Entity\Conference $conference): int
    {
        return (int) $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->andWhere('c.conference = :conference')
            ->setParameter('conference', $conference)
            ->getQuery()
            ->getSingleScalarResult();
    }

    //    /**
    //     * @return Comment[] Returns an array of Comment objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Comment
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
