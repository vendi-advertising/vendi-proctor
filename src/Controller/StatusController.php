<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\WebsiteRepository;

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
