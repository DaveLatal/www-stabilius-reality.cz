<?php
// src/DTO/NewsItemDTO.php
namespace App\DTO;

final readonly class NewsItemDTO
{
    public function __construct(
        public string $title,
        public ?string $link,
        public ?string $image,
        public ?string $description,
        public ?\DateTimeImmutable $pubDate,

    ) {}

    public static function listFromRss(string $xmlString): array
    {
        $list = [];
        $xml = new \SimpleXMLElement($xmlString);

        // RSS items
        foreach ($xml->channel->item as $item) {
            $pubDate = null;
            if (!empty((string) $item->pubDate)) {
                try {
                    $pubDate = new \DateTimeImmutable((string) $item->pubDate);
                } catch (\Exception $e) {
                    // ignore parse errors
                }
            }

            $list[] = new self(
                title: trim((string) $item->title),
                link: isset($item->link) ? trim((string) $item->link) : null,
                image: isset($item->enclosure) ? trim((string) $item->enclosure["url"]) : null,
                description: isset($item->description) ? trim((string) $item->description) : null,
                pubDate: $pubDate
            );
        }

        return $list;
    }
}
