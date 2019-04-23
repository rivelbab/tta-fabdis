<?php

namespace App\Repository;

use App\Entity\GroupeRemise;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method GroupeRemise|null find($id, $lockMode = null, $lockVersion = null)
 * @method GroupeRemise|null findOneBy(array $criteria, array $orderBy = null)
 * @method GroupeRemise[]    findAll()
 * @method GroupeRemise[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupeRemiseRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GroupeRemise::class);
    }

//    /**
//     * @return GroupeRemise[] Returns an array of GroupeRemise objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GroupeRemise
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
