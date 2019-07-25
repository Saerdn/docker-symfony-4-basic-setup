<?php

namespace App\Repository;

use App\Entity\ChildClass;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ChildClass|null find($id, $lockMode = null, $lockVersion = null)
 * @method ChildClass|null findOneBy(array $criteria, array $orderBy = null)
 * @method ChildClass[]    findAll()
 * @method ChildClass[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChildClassRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ChildClass::class);
    }

    // /**
    //  * @return ChildClass[] Returns an array of ChildClass objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ChildClass
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
