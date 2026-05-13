<?php

namespace App\Repository;

use App\DTO\NewsItemDTO;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class NewsFetcherRepository
{
    private HttpClientInterface $client;

    public function __construct()
    {
        $this->client = HttpClient::create([
            'timeout' => 80,
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120 Safari/537.36',
                'Accept' => 'application/xml,text/xml;q=0.9,*/*;q=0.8',
            ],
        ]);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function fetchNews(): array
    {
        $response = $this->client->request(
            'GET',
            'https://www.realitycechy.cz/rss/rss_reality.php'
        );

        $xmlString = $response->getContent();

        return NewsItemDTO::listFromRss($xmlString);
    }


    /**
     * Filter properties in-memory with optional search, categories, sorting, and limit
     *
     * @param NewsItemDTO[] $news
     * @param int|null $limit Max number of results
     *
     * @return NewsItemDTO[]
     */
    public function limitNews(
        array $news,
        ?int $limit = null
    ): array {
        $filtered = [];

        // 3️⃣ Limit
        if ($limit !== null) {
            $filtered = array_slice($news, 0, $limit);
        }

        return array_values($filtered);


    }
}
