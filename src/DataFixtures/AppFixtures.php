<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Website;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $preload = [
            ['inlandpackaging.com', '159.203.98.112'],
            ['vendiadvertising.com'],
            ['titan.vendiadvertising.com', null, 5001],
        ];

        foreach ($preload as $item) {
            $website = new Website();
            $website->setDomain(array_shift($item));
            if (count($item)) {
                $website->setIp(array_shift($item));
            }
            if (count($item)) {
                $website->setPort((int)array_shift($item));
            }

            $manager->persist($website);
        }

        $manager->flush();
    }
}
