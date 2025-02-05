<?php

declare(strict_types=1);

namespace App\Repository;

use App\ValueObject\IssueStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Issue;

class IssueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Issue::class);
    }

    /**
     * @return mixed
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function countStatusBacklog(): mixed
    {
        $qb = $this->getCountQueryBuilder();
        $this->whereStatus($qb, IssueStatus::BACKLOG);
        return $qb->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @return mixed
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function countStatusInProgress(): mixed
    {
        $qb = $this->getCountQueryBuilder();
        $this->whereStatus($qb, IssueStatus::IN_PROGRESS);
        return $qb->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @return mixed
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function countStatusPlanned(): mixed
    {
        $qb = $this->getCountQueryBuilder();
        $this->whereStatus($qb, IssueStatus::PLANNED);
        return $qb->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @return mixed
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function countStatusCompleted(): mixed
    {
        $qb = $this->getCountQueryBuilder();
        $this->whereStatus($qb, IssueStatus::COMPLETED);
        return $qb->getQuery()
            ->getSingleScalarResult();
    }


    private function getCountQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('i')
            ->select('COUNT(i.id)');
    }


    private function whereStatus(QueryBuilder $query, string $status): void
    {
        $query
            ->andWhere('i.status = :status')
            ->setParameter('status', $status);
    }
}
