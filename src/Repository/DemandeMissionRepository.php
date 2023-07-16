<?php

namespace App\Repository;

use App\Entity\DemandeMission;
use App\Entity\Employe;
use App\Entity\Voiture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DemandeMission>
 *
 * @method DemandeMission|null find($id, $lockMode = null, $lockVersion = null)
 * @method DemandeMission|null findOneBy(array $criteria, array $orderBy = null)
 * @method DemandeMission[]    findAll()
 * @method DemandeMission[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DemandeMissionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DemandeMission::class);
    }

    public function save(DemandeMission $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(DemandeMission $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    //    /**
    //     * @return DemandeMission[] Returns an array of DemandeMission objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('d.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    public function findOneByDateAndVoiture($DatDeb, $DatFin, $id, $emp, $voiture): ?DemandeMission
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.DateDebut = :datdeb')
            ->andWhere('d.DateFin = :datfin')
            ->andWhere('d.id != :id')
            ->join('d.voiture', 'v')
            ->andWhere('v.id = :voiture')
            ->setParameter('voiture', $voiture)
            ->join('d.employe', 'e')
            ->andWhere('e.id = :emp')
            ->setParameter('emp', $emp)
            ->setParameter('datdeb', $DatDeb)
            ->setParameter('datfin', $DatFin)
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }
    public function findOneByvoitureandemploye($voiture, $emp)
    {
        return $this->createQueryBuilder('d')
            ->join('d.voiture', 'v')
            ->andWhere('v.id = :voiture')
            ->setParameter('voiture', $voiture)
            ->join('d.employe', 'e')
            ->andWhere('e.id = :emp')
            ->setParameter('emp', $emp)
            ->getQuery()
            ->getResult();
    }
    public function findByDate($user)
    {
        $query = $this->createQueryBuilder('d')
            ->select('SUBSTRING(d.DateDebut,1,7) as Month , COUNT(d) as count')
            ->join('d.user', 'u')
            ->andWhere('u.id = :user')
            ->andWhere('d.ordvalid = :ordvalid')
            ->setParameter('user', $user)
            ->setParameter('ordvalid', true)
            ->groupBy('Month');
        return $query->getQuery()->getResult();
    }
    public function findByDateSg()
    {
        $query = $this->createQueryBuilder('d')
            ->select('SUBSTRING(d.DateDebut,1,7) as Month , COUNT(d) as count')
            ->andWhere('d.valider = :valider')
            ->setParameter('valider', true)
            ->groupBy('Month');
        return $query->getQuery()->getResult();
    }
    public function findByPayer()
    {
        $query = $this->createQueryBuilder('d')
            ->select('SUBSTRING(d.DateDebut,1,7) as Month , COUNT(d) as count')
            ->andWhere('d.payer = :payer')
            ->setParameter('payer', true)
            ->groupBy('Month');
        return $query->getQuery()->getResult();
    }

    // public function PaginationQuery()
    // {
    //     return $this->createQueryBuilder('d')
    //         ->orderBy('d.id', 'ASC')
    //         ->andWhere('d.supprime = :supprime')
    //         ->setParameter('supprime', false)
    //         ->getQuery()
    //         ->getResult();
    // }
    public function findByVoiture()
    {
        $qb = $this->createQueryBuilder('d')
            ->select('v.model as model, COUNT(d.id) as count')
            ->join('d.voiture', 'v')
            ->where('d.ordvalid = :ordvalid')
            ->setParameter('ordvalid', true)
            ->groupBy('model');

        return $qb->getQuery()->getResult();
    }
}
