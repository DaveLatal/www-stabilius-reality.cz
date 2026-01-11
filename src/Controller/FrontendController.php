<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontendController extends AbstractController
{
    #[Route('/', name: 'front_index')]
    public function index(): Response
    {
        return $this->render('pages/homepage.html.twig');
    }

    #[Route('/nemovitosti', name: 'front_realities')]
    public function realities(): Response
    {
        return $this->render('pages/realities.html.twig');
    }
}
