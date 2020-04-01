<?php

namespace App\Repository;

use App\Entity\TypeEmploi;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;

/**
 * @method TypeEmploi|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeEmploi|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeEmploi[]    findAll()
 * @method TypeEmploi[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeEmploiRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeEmploi::class);
    }

    // u JOIN u.groups g GROUP BY u.id
    /*

    $query = $em->createQuery('SELECT COUNT(u.id) FROM Entities\User u');


    $query = $em->createQuery('SELECT u, count(g.id) FROM Entities\User u JOIN u.groups g GROUP BY u.id');
    */

    /**
     * @return Query
     */
    public function findAllQuery(): Query
    {
        return $this->createQueryBuilder('e')
            ->addSelect('e.id', 'e.libelle', 'SIZE(e.intervenants) as size')
            ->groupBy('e')
            ->orderBy('e.libelle', 'ASC')
            ->getQuery()
        ;
    }

    // /**
    //  * @return TypeEmploi[] Returns an array of TypeEmploi objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TypeEmploi
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
