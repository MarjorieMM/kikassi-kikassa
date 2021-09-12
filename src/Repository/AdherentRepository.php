<?php

namespace App\Repository;

use App\Entity\Adherent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Adherent|null find($id, $lockMode = null, $lockVersion = null)
 * @method Adherent|null findOneBy(array $criteria, array $orderBy = null)
 * @method Adherent[]    findAll()
 * @method Adherent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdherentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Adherent::class);
    }

    /**
     * @return Adherent[] Returns an array of Adherent objects
     */

    public function orderByBiblioField($field, $order)
    {
        return $this->createQueryBuilder('a')
            ->join('a.adhesionBibliotheque', 'biblio')
            ->orderBy('biblio.' . $field, $order)
            ->getQuery()
            ->getResult();
    }

    public function orderByField($field, $order)
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.' . $field, $order)
            ->getQuery()
            ->getResult();
    }

    public function findByNomPrenom($value)
    {
        $pattern = strtolower($value);
        return $this->createQueryBuilder('o')
            ->where('LOWER(o.nom) LIKE :val')
            ->orWhere('LOWER(o.prenom) LIKE :val')
            ->setParameter('val', $pattern . '%')
            ->getQuery()
            ->getResult();
    }
}