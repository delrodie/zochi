<?php

namespace App\Repository;

use App\Entity\Activite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Activite|null find($id, $lockMode = null, $lockVersion = null)
 * @method Activite|null findOneBy(array $criteria, array $orderBy = null)
 * @method Activite[]    findAll()
 * @method Activite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActiviteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Activite::class);
    }

    /**
     * @return mixed
     */
    public function findListByDesc()
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.createdAt', 'DESC')
            ->getQuery()->getResult()
            ;
    }

    /**
     * Liste des activités selon la branche
     *
     * @param $branche
     * @return mixed
     */
    public function findByBranche($branche)
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.projet', 'p')
            ->leftJoin('p.branche', 'b')
            ->where('b.id = :branche')
            ->orderBy('a.id', 'DESC')
            ->setParameter('branche', $branche)
            ->getQuery()->getResult()
            ;
    }

    /**
     * Liste des activites selon le projet
     *
     * @param $projet
     * @return mixed
     */
    public function findByProjet($projet)
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.projet', 'p')
            ->where('p.id = :projet')
            ->setParameter('projet', $projet)
            ->getQuery()->getResult()
            ;
    }

    // /**
    //  * @return Activite[] Returns an array of Activite objects
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
    public function findOneBySomeField($value): ?Activite
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
