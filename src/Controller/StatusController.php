<?php declare(strict_types=1);

namespace App\Controller;

use App\Repository\WebsiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class StatusController extends AbstractController
{
    /**
     * @Route("/status", name="status")
     */
    public function index(WebsiteRepository $websiteRepository)
    {
        $websites = $websiteRepository-> findAll();

        return $this->render('status/index.html.twig', [
            'websites' => $websites,
        ]);
    }
}
