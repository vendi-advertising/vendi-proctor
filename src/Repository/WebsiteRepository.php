<?php

declare(strict_types=1);

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

        $query = $this->createNativeNamedQuery('websiteReadOnlyResult');

        $ret = $query->getResult();
        $new_ret = [];
        foreach($ret as $r){
            $ws = array_shift($r);
            foreach($r as $k => $v){
                $ws->lastScanArray[$k] = $v;
            }
            $new_ret[] = $ws;
        }

        return $new_ret;
    }

}
