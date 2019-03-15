<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Website;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Webmozart\PathUtil\Path;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Filesystem\Filesystem;
use function GuzzleHttp\json_decode;

class AppFixtures extends Fixture
{
    private $kernel;

    /** @var Filesystem */
    private $fileSystem;

    public function __construct(KernelInterface $kernel, Filesystem $fileSystem)
    {
        $this->kernel = $kernel;
        $this->fileSystem = $fileSystem;
    }

    public function load(ObjectManager $manager)
    {
        $preload = [
            ['vendiadvertising.com'],
        ];


        $app_root = $this->kernel->getProjectDir();

        $sample_data = Path::join($app_root, 'sample_data/websites.json');

        if($this->fileSystem->exists($sample_data)){
            $preload = json_decode(file_get_contents($sample_data));
        }

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
