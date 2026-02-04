<?php

namespace App\Controller;

use App\Breadcrumb\BreadcrumbsFactory;
use App\DTO\PropertyDetailDTO;
use App\DTO\PropertyListItemDTO;
use App\Repository\PropertyFetcherRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
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
            "properties"=>$results
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




//    private function fetchDetail(string $id): ?\SimpleXMLElement
//    {
//        try {
//            $response = $this->client->request('GET', 'https://eurobydleni.cz/download/full_list_xml.php', [
//                'query' => [
//                    'username' => '6918',
//                    'password' => '152219',
//                    'property_id' => $id,
//                ],
//            ]);
//        } catch (TransportExceptionInterface $e) {
//            return null;
//        }
//
//        dump($response);
//
//        $content = trim($response->getContent(false));
//        if (str_starts_with($content, '<!DOCTYPE')) {
//            dump('HTML RESPONSE instead of XML for ID: '.$id);
//            return null;
//        }
//
//        libxml_use_internal_errors(true);
//        $xml = simplexml_load_string($content);
//        libxml_clear_errors();
//
//        return $xml ?: null;
//    }


}
