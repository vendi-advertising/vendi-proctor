<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\Website;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Website|null find($id, $lockMode = null, $lockVersion = null)
 * @method Website|null findOneBy(array $criteria, array $orderBy = null)
 * @method Website[]    findAll()
 * @method Website[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WebsiteRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Website::class);
    }

    public function findAllForReadonlyReport()
    {
        throw new \Exception('Not implemented yet');
        $rsm = new ResultSetMapping();
        $rsm->addEntityResult('\App\Entity\WebsiteReadonlyResult', 'w');
        $rsm->addFieldResult('w', 'id', 'id');
        $rsm->addFieldResult('w', 'domain', 'domain');
        $rsm->addFieldResult('w', 'ip', 'ip');
        $rsm->addFieldResult('w', 'tlsScanResults', 'tlsScanResults');
        // $rsm->addFieldResult('w', 'id', 'id');


        $sql = 'SELECT w.*, (SELECT * FROM tls_scan_result t WHERE t.website_id = w.id LIMIT 1) FROM website w';

        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        return $query->getResult();
    }

    // /**
    //  * @return Website[] Returns an array of Website objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Website
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
