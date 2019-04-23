<?php

namespace App\Repository;

use App\Entity\Modelimport;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Modelimport|null find($id, $lockMode = null, $lockVersion = null)
 * @method Modelimport|null findOneBy(array $criteria, array $orderBy = null)
 * @method Modelimport[]    findAll()
 * @method Modelimport[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModelimportRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Modelimport::class);
    }

//    /**
//     * @return Modelimport[] Returns an array of Modelimport objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Modelimport
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
