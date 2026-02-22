<?php

namespace App\Controller;

use App\Repository\PropertyFetcherRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CountingsController extends AbstractController
{
    private PropertyFetcherRepository $propertyFetcherRepository;

    public function __construct(
        PropertyFetcherRepository $propertyFetcherRepository
    )
    {
        $this->propertyFetcherRepository = $propertyFetcherRepository;
    }

    #[Route('/get-countings-for-button', name: 'get_countings_for_button')]
    public function realitiesFiltered(Request $request): JsonResponse
    {
        $searchString  = $request->query->get('search');
        $mainCat       = $request->query->get('mainCategory');
        $subCat        = $request->query->get('subCategory');
        $sortBy        = $request->query->get('sortBy');
        $sortDirection = $request->query->get('sortDirection', 'asc');
        $limit         = $request->query->getInt('limit', 500);

        return new JsonResponse($this->propertyFetcherRepository->getActualCountingsForButton($searchString,$mainCat,$subCat,$sortBy,$sortDirection,$limit));
    }
}
