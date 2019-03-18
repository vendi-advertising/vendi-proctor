<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\UptimeResult;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UptimeResult|null find($id, $lockMode = null, $lockVersion = null)
 * @method UptimeResult|null findOneBy(array $criteria, array $orderBy = null)
 * @method UptimeResult[]    findAll()
 * @method UptimeResult[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UptimeResultRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UptimeResult::class);
    }

    // /**
    //  * @return UptimeResult[] Returns an array of UptimeResult objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UptimeResult
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
