<?php

namespace App\Repository;

use App\Entity\Intervenant;
use App\Entity\IntervenantSearch;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;

/**
 * @method Intervenant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Intervenant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Intervenant[]    findAll()
 * @method Intervenant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IntervenantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Intervenant::class);
    }

    /**
     * @return Query
     */
    public function findAllQuery(): Query
    {
        return $this->createQueryBuilder('i')
            ->addSelect('i', 'e', 'di', 'do')
            ->innerJoin('i.emploi', 'e')
            ->innerJoin('i.diplome', 'di')
            ->innerJoin('i.domaines', 'do')
            ->orderBy('i.nom', 'ASC')
            ->getQuery()
        ;
    }

    /**
     * @return Query
     */
    public function searchIntervenantQuery(IntervenantSearch $search): Query
    {
        $res = $this->createQueryBuilder('i')
            ->addSelect('i', 'e', 'di', 'do')
            ->innerJoin('i.emploi', 'e')
            ->innerJoin('i.diplome', 'di')
            ->innerJoin('i.domaines', 'do');

        if ($search->getNom()) $res->andWhere('i.nom LIKE :nom')->setParameter('nom', $search->getNom() . '%');

        if ($search->getPrenom()) $res->andWhere('i.prenom LIKE :prenom')->setParameter('prenom', $search->getPrenom() . '%');

        if ($search->getEmploi()) $res->andWhere('i.emploi = :emploi')->setParameter('emploi', $search->getEmploi());

        if ($search->getDiplome()) $res->andWhere('i.diplome = :diplome')->setParameter('diplome', $search->getDiplome());
        else if ($search->getNiveau()) {
            $res->andWhere('di.niveau = :niveau')->setParameter('niveau', $search->getNiveau());
        }

        if ($search->getDate()) {
            $res->andWhere('i.dateMajCv <= :date')->setParameter('date', $search->getDate()->format('Y-m-d'));
        }

        if ($search->getDomaines()->count() > 0) {

            $k = 0;
            foreach ($search->getDomaines() as $domaine) {

                $k++;
                $res->andWhere(":domaine$k MEMBER OF i.domaines")->setParameter("domaine$k", $domaine);

            }

        }

        $res->orderBy('i.nom', 'ASC');

        return $res->getQuery();
    }

    // /**
    //  * @return Intervenant[] Returns an array of Intervenant objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Intervenant
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
