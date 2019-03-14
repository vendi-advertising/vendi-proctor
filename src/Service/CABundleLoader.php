<?php

declare(strict_types=1);

namespace App\Service;

use ParagonIE\Certainty\RemoteFetch;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;
use Webmozart\PathUtil\Path;

class CABundleLoader
{
    private $kernel;

    private $fileSystem;

    public function __construct(KernelInterface $kernel, Filesystem $fileSystem)
    {
        $this->kernel = $kernel;
        $this->fileSystem = $fileSystem;
    }

    public function get_most_recent_pem_file(bool $force = false) : string
    {
        static $ca_bundle_filepath;

        //Only do this once for the app
        if($force || !$ca_bundle_filepath){

            $app_root = $this->kernel->getProjectDir();

            //Folder for our CA stuff
            $local_ca_path = Path::join($app_root, 'var/ca-bundle');

            //mkdir doesn't fail if the directory exists, no need to check for it first
            $this->fileSystem->mkdir($local_ca_path);

            //Download everything
            $ca_bundle = (new RemoteFetch($local_ca_path))->getLatestBundle();

            //Get the path for the most recent PEM file
            $ca_bundle_filepath = $ca_bundle->getFilePath();
        }

        return $ca_bundle_filepath;
    }
}
