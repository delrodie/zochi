<?php

namespace App\Repository;

use App\Entity\Joti;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Joti|null find($id, $lockMode = null, $lockVersion = null)
 * @method Joti|null findOneBy(array $criteria, array $orderBy = null)
 * @method Joti[]    findAll()
 * @method Joti[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JotiRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Joti::class);
    }

    // /**
    //  * @return Joti[] Returns an array of Joti objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('j.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Joti
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
