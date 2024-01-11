<?php

namespace App\Repository;

use App\Entity\Becado;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Becado|null find($id, $lockMode = null, $lockVersion = null)
 * @method Becado|null findOneBy(array $criteria, array $orderBy = null)
 * @method Becado[]    findAll()
 * @method Becado[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BecadoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Becado::class);
    }

    // /**
    //  * @return Becado[] Returns an array of Becado objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Becado
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
