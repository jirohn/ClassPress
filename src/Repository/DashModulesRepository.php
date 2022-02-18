<?php

namespace App\Repository;

use App\Entity\DashModules;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DashModules|null find($id, $lockMode = null, $lockVersion = null)
 * @method DashModules|null findOneBy(array $criteria, array $orderBy = null)
 * @method DashModules[]    findAll()
 * @method DashModules[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DashModulesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DashModules::class);
    }

    // /**
    //  * @return DashModules[] Returns an array of DashModules objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DashModules
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
