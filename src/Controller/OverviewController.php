<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class OverviewController extends AbstractController
{
    /**
     * @Route("/", name="overview")
     */
    public function index()
    {
        return $this->render('overview/index.html.twig', []);
    }
}
