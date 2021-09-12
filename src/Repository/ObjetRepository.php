<?php

namespace App\Repository;

use App\Entity\Objet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Objet|null find($id, $lockMode = null, $lockVersion = null)
 * @method Objet|null findOneBy(array $criteria, array $orderBy = null)
 * @method Objet[]    findAll()
 * @method Objet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ObjetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Objet::class);
    }

    /**
     * @return Objet[] Returns an array of Objet objects
     */

    public function orderByField($field, $order)
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.' . $field, $order)
            ->getQuery()
            ->getResult();
    }

    public function findByText($value)
    {
        $pattern = strtolower($value);
        return $this->createQueryBuilder('o')
            ->where('LOWER(o.denomination) LIKE :val')
            ->orWhere('LOWER(o.marque) LIKE :val')
            ->setParameter('val', $pattern . '%')
            ->getQuery()
            ->getResult();
    }
}
