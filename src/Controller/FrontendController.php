<?php

namespace App\Controller;

use App\Breadcrumb\BreadcrumbsFactory;
use App\DTO\PropertyDetailDTO;
use App\DTO\PropertyListItemDTO;
use App\Repository\NewsFetcherRepository;
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
    private NewsFetcherRepository $newsFetcherRepository;


    public function __construct(
        RealityRepository $realityRepository,
        PropertyFetcherRepository $propertyFetcherRepository,
        NewsFetcherRepository $newsFetcherRepository
    )
    {
        $this->realityRepository = $realityRepository;
        $this->propertyFetcherRepository = $propertyFetcherRepository;
        $this->newsFetcherRepository = $newsFetcherRepository;

    }

    #[Route('/', name: 'front_homepage')]
    public function index(): Response
    {

        $properties = $this->propertyFetcherRepository->fetchProperties();
//        $news = $this->newsFetcherRepository->fetchNews();

        $resultsHp = $this->propertyFetcherRepository->filterProperties(
            $properties,
            null,
            null,
            null,
            sortBy: 'city',
            sortDirection: 'asc',
            limit: 6
        );
//        dump($news);
        return $this->render('pages/homepage.html.twig',[
            "properties"=>$resultsHp,
//            "news"=>$news
        ]);
    }

    #[Route('/nemovitosti', name: 'front_realities')]
    public function realities( BreadcrumbsFactory $breadcrumbsFactory): Response
    {
        $breadcrumbs = $breadcrumbsFactory->create();
        $properties = $this->propertyFetcherRepository->fetchProperties();
//        $news = $this->newsFetcherRepository->fetchNews();

        $allCountings = $this->propertyFetcherRepository->getCountingsForCategories();
        $results = $this->propertyFetcherRepository->filterProperties(
            $properties,
            null,
            null,
            null,
            sortBy: 'city',
            sortDirection: 'asc',
            limit: 50
        );

        return $this->render('pages/realities.html.twig',[
            "breadcrumbs"=>$breadcrumbs,
            "catCountings"=>$allCountings,
            "properties"=>$results,
//            "news"=>$news
        ]);
    }



    #[Route('/nemovitosti/filter', name: 'front_realities_filtered')]
    public function realitiesFiltered(
        Request $request,
        BreadcrumbsFactory $breadcrumbsFactory
    ): Response {
        $breadcrumbs = $breadcrumbsFactory->create();

        // ✅ READ FROM QUERY (GET), NOT POST
        $searchString  = $request->query->get('search');
        $mainCat       = $request->query->get('mainCategory');
        $subCat        = $request->query->get('subCategory');
        $sortBy        = $request->query->get('sortBy');
        $sortDirection = $request->query->get('sortDirection', 'asc');
        $limit         = $request->query->getInt('limit', 500);

        // Fetch properties ONCE
        $properties = $this->propertyFetcherRepository->fetchProperties();
//        $news = $this->newsFetcherRepository->fetchNews();

        // Category counters
        $allCountings = $this->propertyFetcherRepository->getCountingsForCategories(
            $searchString,
            $mainCat,
            $subCat,
            $sortBy,
            $sortDirection,
            $limit
        );

        // Actual filtered results
        $results = $this->propertyFetcherRepository->filterProperties(
            $properties,
            $searchString,
            $mainCat,
            $subCat,
            sortBy: $sortBy,
            sortDirection: $sortDirection,
            limit: $limit
        );

        return $this->render('pages/realities.html.twig', [
            'breadcrumbs'  => $breadcrumbs,
            'catCountings' => $allCountings,
            'properties'   => $results,
//            "news"=>$news
        ]);
    }

    #[Route('/nemovitosti/json-filter', name: 'front_realities_filtered_json')]
    public function realitiesFilteredJson(Request $request): JsonResponse
    {
        $data = $request->request->all(); // JSON / POST is correct here

        return $this->json([
            'redirect' => $this->generateUrl('front_realities_filtered', [
                'search'        => $data['search'] ?? null,
                'mainCategory'  => $data['mainCategory'] ?? null,
                'subCategory'   => $data['subCategory'] ?? null,
                'sortBy'        => $data['sortBy'] ?? null,
                'sortDirection' => $data['sortDirection'] ?? 'asc',
                'limit'         => $data['limit'] ?? 500,
            ]),
        ]);
    }


    #[Route('/nemovitost/{id}', name: 'front_realitiy_detail')]
    public function reality_detail($id, BreadcrumbsFactory $breadcrumbsFactory): Response
    {
        $breadcrumbs = $breadcrumbsFactory->create();
        $property=$this->propertyFetcherRepository->fetchPropertyDetail($id);
        $googleMapsApiKey = $_ENV['GOOGLE_MAPS_API_KEY'];

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
//        $news = $this->newsFetcherRepository->fetchNews();

        $breadcrumbs = $breadcrumbsFactory->create();
        return $this->render('pages/onas.html.twig',[
            "breadcrumbs"=>$breadcrumbs,
//            "news"=>$news
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
//        $news = $this->newsFetcherRepository->fetchNews();

        $breadcrumbs = $breadcrumbsFactory->create();
        return $this->render('pages/kontakty.html.twig',[
            "breadcrumbs"=>$breadcrumbs,
            "google_maps_api_key"=>$googleMapsApiKey,
//            "news"=>$news
        ]);
    }




}
