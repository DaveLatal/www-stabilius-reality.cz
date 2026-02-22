<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class LocationController extends AbstractController
{
    #[Route('/api/locations/search', name: 'api_location_search')]
    public function search(
        Request $request,
        HttpClientInterface $httpClient
    ): JsonResponse {
        $query = $request->query->get('q');

        if (!$query || mb_strlen($query) < 2) {
            return $this->json([]);
        }

        $response = $httpClient->request('GET', 'https://nominatim.openstreetmap.org/search', [
            'query' => [
                'q' => $query,
                'countrycodes' => 'cz',
                'format' => 'json',
                'addressdetails' => 1,
                'limit' => 10,
            ],
            'headers' => [
                // POVINNÉ – jinak tě Nominatim může bloknout
                'User-Agent' => 'YourProjectName/1.0 (info@yourdomain.cz)',
            ],
        ]);

        $data = $response->toArray();

        $results = array_map(static function ($item) {
            return [
                'label' => $item['display_name'],
                'city'  => $item['address']['city']
                    ?? $item['address']['town']
                        ?? $item['address']['village']
                        ?? null,
                'district' => $item['address']['county'] ?? null,
                'region'   => $item['address']['state'] ?? null,
            ];
        }, $data);

        return $this->json($results);
    }
}
