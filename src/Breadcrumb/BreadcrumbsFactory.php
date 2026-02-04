<?php

// src/Breadcrumb/BreadcrumbsFactory.php
namespace App\Breadcrumb;

use App\DTO\BreadcrumbItem;
use App\DTO\Breadcrumbs;
use App\Repository\PropertyFetcherRepository;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class BreadcrumbsFactory
{
    private PropertyFetcherRepository $propertyFetcherRepository;

    public function __construct(
        private RequestStack $requestStack,
        private RouterInterface $router,
        PropertyFetcherRepository $propertyFetcherRepository
    ) {
        $this->propertyFetcherRepository = $propertyFetcherRepository;

    }

    public function create(): Breadcrumbs
    {
        $request = $this->requestStack->getCurrentRequest();
        $routeName = $request?->attributes->get('_route');
        $routeParams = $request?->attributes->get('_route_params', []);
        $propertyName = null;

        if ($routeName === 'front_realitiy_detail' && isset($routeParams['id'])) {
            $property = $this->propertyFetcherRepository->fetchPropertyDetail($routeParams['id']);
            $propertyName = $property?->title;
        }

        $items = match ($routeName) {


            'front_realities' => [
                new BreadcrumbItem('Nemovitosti'),
            ],
            'front_services' => [
                new BreadcrumbItem('Služby'),
            ],
            'front_about_us' => [
                new BreadcrumbItem('O nás'),
            ],
            'front_contacts' => [
                new BreadcrumbItem('Kontakty'),
            ],
            'front_realitiy_detail' => [
                new BreadcrumbItem('Nemovitosti', $this->router->generate('front_realities')),
                new BreadcrumbItem($propertyName ?? 'Nemovitost'),
            ],

            default => [
                new BreadcrumbItem('Home', $this->router->generate('front_homepage')),
            ],
        };

        return new Breadcrumbs($items);
    }
}
