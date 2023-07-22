<?php

namespace App\Repository;

use App\Entity\PuzzleOpening;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PuzzleOpening>
 *
 * @method PuzzleOpening|null find($id, $lockMode = null, $lockVersion = null)
 * @method PuzzleOpening|null findOneBy(array $criteria, array $orderBy = null)
 * @method PuzzleOpening[]    findAll()
 * @method PuzzleOpening[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PuzzleOpeningRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PuzzleOpening::class);
    }

    public function add(PuzzleOpening $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(PuzzleOpening $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return PuzzleOpening[] Returns an array of PuzzleOpening objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PuzzleOpening
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
