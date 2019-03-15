<?php declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WebsiteRepository")
 */
class WebsiteReadonlyResult extends Website
{
    public $lastScan_dateTimeCreated;

    public $lastScan_dateValidFrom;

    public $lastScan_dateValidTo;

    public $lastScan_isValid;
}
