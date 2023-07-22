<?php

namespace App\Repository;

use App\Entity\PuzzleTheme;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PuzzleTheme>
 *
 * @method PuzzleTheme|null find($id, $lockMode = null, $lockVersion = null)
 * @method PuzzleTheme|null findOneBy(array $criteria, array $orderBy = null)
 * @method PuzzleTheme[]    findAll()
 * @method PuzzleTheme[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PuzzleThemeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PuzzleTheme::class);
    }

    public function add(PuzzleTheme $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(PuzzleTheme $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return PuzzleTheme[] Returns an array of PuzzleTheme objects
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

//    public function findOneBySomeField($value): ?PuzzleTheme
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
