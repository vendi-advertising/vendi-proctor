<?php

namespace App\Controller\Site;

use App\Form\WebsiteFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class AddController extends AbstractController
{
    /**
     * @Route("/site/add", name="site_add")
     */
    public function index(EntityManagerInterface $em, Request $request)
    {

        $form = $this->createForm(WebsiteFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $website = $form->getData();

            $em->persist($website);
            $em->flush();
        }

        return $this
                    ->render(
                        'site/add/index.html.twig',
                        [
                            'websiteForm' => $form->createView()
                        ]
                );
    }
}
