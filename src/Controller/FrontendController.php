<?php

namespace App\Controller;

use App\Breadcrumb\BreadcrumbsFactory;
use App\DTO\PropertyDetailDTO;
use App\DTO\PropertyListItemDTO;
use App\Repository\PropertyFetcherRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\RealityRepository;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\UX\Map\Map;
use Symfony\UX\Map\Point;

class FrontendController extends AbstractController
{

    private RealityRepository $realityRepository;
    private PropertyFetcherRepository $propertyFetcherRepository;


    public function __construct(
        RealityRepository $realityRepository,
        PropertyFetcherRepository $propertyFetcherRepository
    )
    {
        $this->realityRepository = $realityRepository;
        $this->propertyFetcherRepository = $propertyFetcherRepository;

    }

    #[Route('/', name: 'front_homepage')]
    public function index(): Response
    {

        $properties = $this->propertyFetcherRepository->fetchProperties();

        $resultsHp = $this->propertyFetcherRepository->filterProperties(
            $properties,
            null,
            null,
            null,
            sortBy: 'city',
            sortDirection: 'asc',
            limit: 6
        );

        return $this->render('pages/homepage.html.twig',[
            "properties"=>$resultsHp
        ]);
    }

    #[Route('/nemovitosti', name: 'front_realities')]
    public function realities( BreadcrumbsFactory $breadcrumbsFactory): Response
    {
        $breadcrumbs = $breadcrumbsFactory->create();
        $properties = $this->propertyFetcherRepository->fetchProperties();
        $allCountings = $this->propertyFetcherRepository->getCountingsForCategories();
        $results = $this->propertyFetcherRepository->filterProperties(
            $properties,
            null,
            null,
            null,
            sortBy: 'city',
            sortDirection: 'asc',
            limit: 10
        );

        return $this->render('pages/realities.html.twig',[
            "breadcrumbs"=>$breadcrumbs,
            "catCountings"=>$allCountings,
            "properties"=>$results
        ]);
    }



    #[Route('/nemovitosti/filter', name: 'front_realities_filtered')]
    public function realitiesFiltered(Request $request,BreadcrumbsFactory $breadcrumbsFactory): Response
    {
        $breadcrumbs = $breadcrumbsFactory->create();

        $properties = $this->propertyFetcherRepository->fetchProperties();
        $data = $request->request->all();
        $searchString =  $data["search"] ?? null;
        $mainCat =  $data["mainCategory"] ?? null;
        $subCat =  $data["subCategory"] ?? null;
        $sortBy =  $data["sortBy"] ?? null;
        $sortDirection =  $data["sortDirection"] ?? "asc";
        $limit =  $data["limit"] ?? 10;

        $allCountings = $this->propertyFetcherRepository->getCountingsForCategories(
            $searchString,
            $mainCat,
            $subCat,
            $sortBy,
            $sortDirection,
            $limit
        );

        $results = $this->propertyFetcherRepository->filterProperties(
            $properties,
            $searchString,
            $mainCat,
            $subCat,
            sortBy: $sortBy,
            sortDirection: $sortDirection,
            limit: $limit
        );
        dump($results);

        return $this->render('pages/realities.html.twig',[
            "breadcrumbs"=>$breadcrumbs,
            "catCountings"=>$allCountings,
            "properties"=>$results
        ]);
    }


    #[Route('/nemovitosti/json-filter', name: 'front_realities_filtered_json')]
    public function realitiesFilteredJson(Request $request): JsonResponse
    {
        $data = $request->request->all();
        return $this->json([
            'redirect' => $this->generateUrl('front_realities_filtered', [
                'search' => $data['search'] ?? null,
                'mainCategory' => $data['mainCategory'] ?? null,
                'subCategory' => $data['subCategory'] ?? null,
                'sortBy' => $data['sortBy'] ?? null,
                'sortDirection' => $data['sortDirection'] ?? "asc",
                'limit' => $data['limit'] ?? 10,
            ])
        ]);
    }


    #[Route('/nemovitost/{id}', name: 'front_realitiy_detail')]
    public function reality_detail($id, BreadcrumbsFactory $breadcrumbsFactory): Response
    {
        $breadcrumbs = $breadcrumbsFactory->create();
        $property=$this->propertyFetcherRepository->fetchPropertyDetail($id);
        $googleMapsApiKey = $_ENV['GOOGLE_MAPS_API_KEY'];
        dump($property);
//        $reality = $this->realityRepository->findOneBy(['slug' => $slug]);

        $map = (new Map())
            ->center(new Point($property->gpsLat, $property->gpsLng))
            ->zoom(6)
            ->minZoom(3) // Set the minimum zoom level
            ->maxZoom(10) // Set the maximum zoom level
        ;
        $properties = $this->propertyFetcherRepository->fetchProperties();
        $resultsSimiliar = $this->propertyFetcherRepository->filterProperties(
            $properties,
            null,
            $property->columns["typ_nemovitosti"],
            null,
            sortBy: 'city',
            sortDirection: 'asc',
            limit: 3
        );

        return $this->render('pages/reality-detail.html.twig',[
            "breadcrumbs"=>$breadcrumbs,
            "reality"=>$property,
            "google_maps_api_key"=>$googleMapsApiKey,
            "reality_location_map"=>$map,
            "similiar"=>$resultsSimiliar
        ]);
    }


    #[Route('/o-nas', name: 'front_about_us')]
    public function about_us(BreadcrumbsFactory $breadcrumbsFactory): Response
    {

        $breadcrumbs = $breadcrumbsFactory->create();
        return $this->render('pages/onas.html.twig',[
            "breadcrumbs"=>$breadcrumbs
        ]);
    }

    #[Route('/sluzby', name: 'front_services')]
    public function services(BreadcrumbsFactory $breadcrumbsFactory): Response
    {
        $breadcrumbs = $breadcrumbsFactory->create();

        return $this->render('pages/sluzby.html.twig',[
            "breadcrumbs"=>$breadcrumbs
        ]);
    }

    #[Route('/kontakty', name: 'front_contacts')]
    public function contacts(BreadcrumbsFactory $breadcrumbsFactory): Response
    {
        $googleMapsApiKey = $_ENV['GOOGLE_MAPS_API_KEY'];

        $breadcrumbs = $breadcrumbsFactory->create();
        return $this->render('pages/kontakty.html.twig',[
            "breadcrumbs"=>$breadcrumbs,
            "google_maps_api_key"=>$googleMapsApiKey,
        ]);
    }




}
