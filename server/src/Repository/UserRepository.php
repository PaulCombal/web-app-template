<?php

namespace App\Repository;

use App\Entity\User;
use App\Service\PaginatorService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{

    private $ps;
    public function __construct(ManagerRegistry $registry, PaginatorService $paginatorService)
    {
        parent::__construct($registry, User::class);
        $this->ps = $paginatorService;
    }

    public function rawListing($dql) {
        return $this
            ->getEntityManager()
            ->createQuery($dql)
            ->setFirstResult($this->ps->from())
            ->setMaxResults($this->ps->n())
            ->getResult();
    }

    public function rawCount($dql) {
        return $this
            ->getEntityManager()
            ->createQuery($dql)
            ->getSingleScalarResult();
    }

    // TODO: is there a way to make this in one query using DQL?
    public function formattedListing($dql = "SELECT u FROM App:User u ORDER BY u.id", $dql_count = "SELECT COUNT(u.id) FROM App:User u") {
        return [
            'total' => $this->rawCount($dql_count),
            'data' => $this->rawListing($dql)
        ];
    }

    // /**
    //  * @return User[] Returns an array of User objects
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
    public function findOneBySomeField($value): ?User
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
