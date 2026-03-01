<?php

namespace App\Repository;

use App\DTO\CategoryCountingsDTO;
use App\DTO\NewsItemDTO;
use App\DTO\PropertyDetailDTO;
use App\DTO\PropertyListItemDTO;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class NewsFetcherRepository
{
    private HttpClientInterface $client;

    public function __construct()
    {
        $this->client = HttpClient::create([
            'timeout' => 20,
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120 Safari/537.36',
                'Accept' => 'application/xml,text/xml;q=0.9,*/*;q=0.8',
            ],
        ]);
    }

    public function fetchNews(): array
    {
        $response = $this->client->request(
            'GET',
            'https://www.realitycechy.cz/rss/rss_reality.php'
        );

        $xmlString = $response->getContent();

        return $newsItems = NewsItemDTO::listFromRss($xmlString);
    }


}
