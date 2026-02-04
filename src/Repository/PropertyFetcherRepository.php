<?php

namespace App\Repository;

use App\DTO\PropertyDetailDTO;
use App\DTO\PropertyListItemDTO;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class PropertyFetcherRepository
{
    private HttpClientInterface $client;

    public function __construct()
    {
        $this->client = HttpClient::create([   'timeout' => 20,
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120 Safari/537.36',
                'Accept' => 'application/xml,text/xml;q=0.9,*/*;q=0.8',
            ],]);
    }

    public function fetchProperties(): array
    {
        //        $response = $this->client->request('GET', 'https://eurobydleni.cz/download/full_list_xml.php?username=6918&password=152219', [
//            'query' => [
//                'username' => '6918',
//                'password' => '152219',
//                'page' => 1,
////                'type' => 'list', // ← IMPORTANT
//            ],
//        ]);
        $response = $this->client->request('GET', 'https://eurobydleni.cz/download/full_list_xml.php?username=6918&password=152219');

        $xmlString = $response->getContent();
        $items = PropertyListItemDTO::listFromXml($xmlString);
        $properties = [];
        foreach ($items as $item){
            $stringURL='https://eurobydleni.cz/download/full_list_xml.php?username=6918&password=152219&property_id='.$item->id;
            $response_detail = $this->client->request('GET',$stringURL);
            $xmlDetailString = $response_detail->getContent();
            $xml = new \SimpleXMLElement($xmlDetailString);
            $propertyNode = $xml->property;

            $item = PropertyDetailDTO::fromXml(
                $item->id,
                $propertyNode
            );
            $properties[]=$item;
        }

        dump($properties);
        return $properties;
    }

    public function fetchPropertyDetail($id): PropertyDetailDTO
    {
        $stringURL='https://eurobydleni.cz/download/full_list_xml.php?username=6918&password=152219&property_id='.$id;
        $response_detail = $this->client->request('GET',$stringURL);
        $xmlDetailString = $response_detail->getContent();
        $xml = new \SimpleXMLElement($xmlDetailString);
        $propertyNode = $xml->property;

        return PropertyDetailDTO::fromXml(
            $id,
            $propertyNode
        );
    }
}
