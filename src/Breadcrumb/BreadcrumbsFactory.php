<?php

// src/Breadcrumb/BreadcrumbsFactory.php
namespace App\Breadcrumb;

use App\DTO\BreadcrumbItem;
use App\DTO\Breadcrumbs;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;

final class BreadcrumbsFactory
{
    public function __construct(
        private RequestStack $requestStack,
        private RouterInterface $router,
    ) {}

    public function create(): Breadcrumbs
    {
        $request = $this->requestStack->getCurrentRequest();
        $routeName = $request?->attributes->get('_route');
        $routeParams = $request?->attributes->get('_route_params', []);

        $items = match ($routeName) {


            'front_realities' => [
                new BreadcrumbItem('Nemovitosti'),
            ],
            'front_realitiy_detail' => [
                new BreadcrumbItem('Nemovitosti', $this->router->generate('front_realities')),
                new BreadcrumbItem('Nemovitost'),
            ],

            default => [
                new BreadcrumbItem('Home', $this->router->generate('front_homepage')),
            ],
        };

        return new Breadcrumbs($items);
    }
}
