<?php

namespace App\Repository;

use App\Entity\AdhesionBibliotheque;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AdhesionBibliotheque|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdhesionBibliotheque|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdhesionBibliotheque[]    findAll()
 * @method AdhesionBibliotheque[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdhesionBibliothequeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdhesionBibliotheque::class);
    }

     /**
     * @return AdhesionBibliotheque[] Returns an array of AdhesionBibliotheque objects
      */

      public function removeDuplicates(){
        return $this->createQueryBuilder('a')
        ->groupBy('a.categorie_fourmi')
        ->getQuery()
            ->getResult();
      }

      public function filterOut($condition){
        return $this->createQueryBuilder('a')
            ->where('a.categorie_fourmi = :condition')
            ->setParameter('condition', $condition)
            ->getQuery();
      }
    
      /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
        
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AdhesionBibliotheque
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}