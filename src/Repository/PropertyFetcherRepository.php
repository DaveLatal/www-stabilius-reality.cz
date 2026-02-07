<?php

namespace App\Repository;

use App\DTO\CategoryCountingsDTO;
use App\DTO\PropertyDetailDTO;
use App\DTO\PropertyListItemDTO;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class PropertyFetcherRepository
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

    public function fetchProperties(): array
    {
        $response = $this->client->request('GET', 'https://eurobydleni.cz/download/full_list_xml.php?username=6918&password=152219');
        $xmlString = $response->getContent();
        $items = PropertyListItemDTO::listFromXml($xmlString);


        $properties = [];
        foreach ($items as $item) {
            $properties[] = $this->fetchPropertyDetail($item->id);
        }

        return $properties;
    }

    public function fetchPropertyDetail(string $id): PropertyDetailDTO
    {
        $url = 'https://eurobydleni.cz/download/full_list_xml.php?username=6918&password=152219&property_id=' . $id;
        $response = $this->client->request('GET', $url);
        $xml = new \SimpleXMLElement($response->getContent());

        return PropertyDetailDTO::fromXml($id, $xml->property);
    }

    /**
     * Filter properties in-memory with optional search, categories, sorting, and limit
     *
     * @param PropertyDetailDTO[] $properties
     * @param string|null $search Full-text search
     * @param string|null $mainCategory Main category filter
     * @param string|null $subCategory Subcategory filter
     * @param string|null $sortBy Field to sort by ('price', 'title', 'city', etc.)
     * @param string $sortDirection 'asc' or 'desc'
     * @param int|null $limit Max number of results
     *
     * @return PropertyDetailDTO[]
     */
    public function filterProperties(
        array $properties,
        ?string $search = null,
        ?string $mainCategory = null,
        ?string $subCategory = null,
        ?string $sortBy = null,
        string $sortDirection = 'asc',
        ?int $limit = null
    ): array {
        // 1️⃣ Filter
        $filtered = array_filter($properties, function (PropertyDetailDTO $p) use ($search, $mainCategory, $subCategory) {
            if ($search && !$p->matchesSearch($search)) {
                return false;
            }
            if (!$p->matchesCategory($mainCategory, $subCategory)) {
                return false;
            }
            return true;
        });

        // 2️⃣ Sort
        if ($sortBy !== null) {
            usort($filtered, function (PropertyDetailDTO $a, PropertyDetailDTO $b) use ($sortBy, $sortDirection) {
                $valA = $a->{$sortBy} ?? null;
                $valB = $b->{$sortBy} ?? null;

                // Handle numeric sort for price or area
                if (in_array($sortBy, ['price', 'areaLand', 'areaUsable'])) {
                    $valA = floatval(str_replace(',', '.', $valA ?? 0));
                    $valB = floatval(str_replace(',', '.', $valB ?? 0));
                } else {
                    $valA = strtolower((string)($valA ?? ''));
                    $valB = strtolower((string)($valB ?? ''));
                }

                if ($valA === $valB) return 0;

                $result = $valA < $valB ? -1 : 1;

                return $sortDirection === 'asc' ? $result : -$result;
            });
        }

        // 3️⃣ Limit
        if ($limit !== null) {
            $filtered = array_slice($filtered, 0, $limit);
        }

        return array_values($filtered);


    }

    public function getCountingsForCategories(
        ?string $search = null,
        ?string $mainCategory = null,
        ?string $subCategory = null,
        ?string $sortBy = null,
        string $sortDirection = 'asc',
        ?int $limit = null
    ): CategoryCountingsDTO
    {
        $properties = $this->fetchProperties();

        $allCounts = count($this->filterProperties(
            $properties,
            $search,
            null,
            $subCategory,
            sortBy: $sortBy,
            sortDirection: $sortDirection,
            limit: $limit
        ));
        $byty = count($this->filterProperties(
            $properties,
            $search,
            'byty',
            $subCategory,
            sortBy: $sortBy,
            sortDirection: $sortDirection,
            limit: $limit
        ));
        $domy = count($this->filterProperties(
            $properties,
            $search,
            'domy',
            $subCategory,
            sortBy: $sortBy,
            sortDirection: $sortDirection,
            limit: $limit
        ));
        $pozemky = count($this->filterProperties(
            $properties,
            $search,
            'pozemky',
            $subCategory,
            sortBy: $sortBy,
            sortDirection: $sortDirection,
            limit: $limit
        ));
        $komercni = count($this->filterProperties(
            $properties,
            $search,
            'komercni',
            $subCategory,
            sortBy: $sortBy,
            sortDirection: $sortDirection,
            limit: $limit
        ));
        $ostatni = count($this->filterProperties(
            $properties,
            $search,
            'ostatni',
            $subCategory,
            sortBy: $sortBy,
            sortDirection: $sortDirection,
            limit: $limit
        ));


        return new CategoryCountingsDTO(
            $allCounts,
            $byty,
            $domy,
            $pozemky,
            $komercni,
            $ostatni
        );
    }

}
