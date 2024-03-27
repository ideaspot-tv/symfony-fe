<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Task>
 *
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function findForIndex(string $query = null): array
    {
        $qb = $this->createQueryBuilder('t');
        $qb->where('t.isComplete = :isComplete')
            ->setParameter('isComplete', false);
        if ($query) {
            $qb->andWhere('t.summary LIKE :query')
                ->setParameter('query', "%$query%");
        }

        $incompleteQuery = $qb->getQuery();
        $incompleteQuery->setParameter('isComplete', false);
        $incomplete = $incompleteQuery->getResult();

        $completeQuery = $qb->getQuery();
        $completeQuery->setParameter('isComplete', true);
        $complete = $completeQuery->getResult();

        return [
            $incomplete,
            $complete,
        ];
    }

    public function save($task, $flush = false)
    {
        $this->getEntityManager()->persist($task);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove($task, $flush = false)
    {
        $this->getEntityManager()->remove($task);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function removeCompleted()
    {
        $qb = $this->createQueryBuilder('t');
        $qb->delete()->where('t.isComplete = :isComplete')
            ->setParameter('isComplete', true);

        $query = $qb->getQuery();
        $query->execute();
    }
}
