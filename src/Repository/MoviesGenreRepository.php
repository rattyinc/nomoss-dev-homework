<?php

namespace App\Repository;

use App\Entity\MoviesGenre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method MoviesGenre|null find($id, $lockMode = null, $lockVersion = null)
 * @method MoviesGenre|null findOneBy(array $criteria, array $orderBy = null)
 * @method MoviesGenre[]    findAll()
 * @method MoviesGenre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MoviesGenreRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MoviesGenre::class);
    }

    // /**
    //  * @return MoviesGenre[] Returns an array of MoviesGenre objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MoviesGenre
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
