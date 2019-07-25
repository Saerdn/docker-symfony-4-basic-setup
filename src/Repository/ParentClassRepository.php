<?php

namespace App\Repository;

use App\Entity\ParentClass;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method ParentClass|null find($id, $lockMode = null, $lockVersion = null)
 * @method ParentClass|null findOneBy(array $criteria, array $orderBy = null)
 * @method ParentClass[]    findAll()
 * @method ParentClass[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParentClassRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ParentClass::class);
    }

     /**
      * @return ParentClass[] Returns an array of ParentClass objects
      */
    public function findAllByUser(UserInterface $user)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.user = :user')
            ->setParameter('user', $user)
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?ParentClass
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
