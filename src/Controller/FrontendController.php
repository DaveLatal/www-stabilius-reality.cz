<?php

namespace App\Controller;

use App\Breadcrumb\BreadcrumbsFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\RealityRepository;
use Symfony\UX\Map\Map;
use Symfony\UX\Map\Point;

class FrontendController extends AbstractController
{

    private RealityRepository $realityRepository;

    public function __construct(RealityRepository $realityRepository)
    {
        $this->realityRepository = $realityRepository;
    }

    #[Route('/', name: 'front_homepage')]
    public function index(): Response
    {
        return $this->render('pages/homepage.html.twig');
    }

    #[Route('/nemovitosti', name: 'front_realities')]
    public function realities( BreadcrumbsFactory $breadcrumbsFactory): Response
    {
        $breadcrumbs = $breadcrumbsFactory->create();
        return $this->render('pages/realities.html.twig',[
            "breadcrumbs"=>$breadcrumbs
        ]);
    }
    #[Route('/nemovitost/{slug}', name: 'front_realitiy_detail')]
    public function reality_detail($slug, BreadcrumbsFactory $breadcrumbsFactory): Response
    {
        $breadcrumbs = $breadcrumbsFactory->create();



        $reality = $this->realityRepository->findOneBy(['slug' => $slug]);

        $map = (new Map())
            ->center(new Point(46.903354, 1.888334))
            ->zoom(6)
            ->minZoom(3) // Set the minimum zoom level
            ->maxZoom(10) // Set the maximum zoom level
        ;

        return $this->render('pages/reality-detail.html.twig',[
            "breadcrumbs"=>$breadcrumbs,
            "reality"=>$reality,
            "map"=>$map
        ]);
    }
}
