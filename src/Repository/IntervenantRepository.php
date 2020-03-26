<?php

namespace App\Repository;

use App\Entity\Intervenant;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

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
     * @return Intervenant[] Returns an array of Intervenant objects
     */
    public function searchIntervenant($data)
    {
        $res = $this->createQueryBuilder('i');

        if ($data['nom']) $res->andWhere('i.nom LIKE :val')->setParameter('val', $data['nom'] . '%');

        if ($data['prenom']) $res->andWhere('i.prenom LIKE :val')->setParameter('val', $data['prenom']);

        if ($data['emploi']) $res->andWhere('i.emploi = :val')->setParameter('val', $data['emploi']);

        if ($data['diplome']) $res->andWhere('i.diplome = :val')->setParameter('val', $data['diplome']);
        else if ($data['niveau']) {
            $res->innerJoin('i.diplome', 'di')->andWhere('di.niveau = :val')->setParameter('val', $data['niveau']);
        }

        if (!$data['domaines']->isEmpty()) {

            foreach ($data['domaines']->toArray() as $k => $domaine) {

                $res->andWhere(":domaine$k MEMBER OF i.domaines")->setParameter("domaine$k", $domaine);

            }

        }

        return $res->getQuery()
                   ->getResult();
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
