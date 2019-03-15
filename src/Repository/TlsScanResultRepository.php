<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\TlsScanResult;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TlsScanResult|null find($id, $lockMode = null, $lockVersion = null)
 * @method TlsScanResult|null findOneBy(array $criteria, array $orderBy = null)
 * @method TlsScanResult[]    findAll()
 * @method TlsScanResult[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TlsScanResultRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TlsScanResult::class);
    }

    // /**
    //  * @return TlsScanResult[] Returns an array of TlsScanResult objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TlsScanResult
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
