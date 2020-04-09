<?php

namespace App\Repository;

use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Utilisateur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Utilisateur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Utilisateur[]    findAll()
 * @method Utilisateur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UtilisateurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Utilisateur::class);
    }

    // /**
    //  * @return Utilisateur[] Returns an array of Utilisateur objects
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
    public function findOneBySomeField($value): ?Utilisateur
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    /**
     * @param string $getUsername
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findByUser(string $getUsername)
    {
        return $this->createQueryBuilder('u')
            ->leftJoin('u.user', 'us')
            ->where('us.username = :username')
            ->setParameter('username', $getUsername)
            ->getQuery()->getOneOrNullResult()
            ;
    }

    /**
     * Liste des profiles correspondante à la liste
     *
     * @param $profile
     * @return mixed
     */
    public function searchProfile($profile)
    {
        return $this->createQueryBuilder('u')
            ->leftJoin('u.user', 'us')
            ->where('us.username LIKE :profile')
            ->orWhere('u.nom LIKE :profile')
            ->orWhere('u.prenoms LIKE :profile')
            ->setParameter('profile', '%'.$profile.'%')
            ->getQuery()->getResult()
            ;
    }
}
