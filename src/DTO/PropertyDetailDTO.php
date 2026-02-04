<?php

namespace App\DTO;

final readonly class PropertyDetailDTO
{
    public string $searchText;       // precomputed searchable text
    public ?string $mainCategory;    // typ_nemovitosti
    public ?string $subCategory;     // typ_nemovitosti_u or typ_prodeje

    public function __construct(
        public string $id,

        public ?string $title,
        public ?string $description,
        public ?string $price,
        public ?string $currency,

        public ?string $gpsLat,
        public ?string $gpsLng,
        public ?string $address,
        public ?string $city,
        public ?string $district,
        public ?string $region,
        public ?string $country,

        public ?string $propertyType,
        public ?string $propertySubType,
        public ?string $saleType,

        public ?string $areaLand,
        public ?string $areaUsable,

        public ?string $createdAt,
        public ?string $modifiedAt,

        public ?string $brokerName,
        public ?string $brokerEmail,
        public ?string $brokerPhone,

        public array $images,
        public array $columns,

        // --- computed categories ---
        ?string $mainCategory = null,
        ?string $subCategory = null,
    ) {
        $this->mainCategory = $mainCategory;
        $this->subCategory = $subCategory;

        // --- precompute search text ---
        $parts = [
            $this->title,
            $this->description,
            $this->city,
            $this->mainCategory,
            $this->subCategory,
        ];

        $this->searchText = mb_strtolower(implode(' ', array_filter($parts)));
    }

    public static function fromXml(string $id, \SimpleXMLElement $property): self
    {
        // 1️⃣ Parse all columns
        $columns = [];
        foreach ($property->column as $column) {
            $name = (string) $column['name'];
            $value = trim((string) $column->column_text_value);
            if ($value !== '') {
                $columns[$name] = $value;
            }
        }

        // 2️⃣ Images
        $images = [];
        if (isset($property->images->photo)) {
            foreach ($property->images->photo as $photo) {
                $images[] = (string) $photo->photo_url;
            }
        }

        // 3️⃣ Broker
        $broker = $property->broker ?? null;

        // --- compute categories ---
        $mainCategory = isset($columns['typ_nemovitosti'])
            ? mb_strtolower(trim((string) $columns['typ_nemovitosti']))
            : null;

        $subCategory = $columns['typ_nemovitosti_u'] ?? $columns['typ_prodeje'] ?? null;
        $subCategory = $subCategory ? mb_strtolower(trim((string)$subCategory)) : null;

        return new self(
            id: $id,
            title: $columns['popisz'] ?? null,
            description: $columns['popis'] ?? null,
            price: $columns['cena'] ?? null,
            currency: $columns['cena_mena'] ?? null,
            gpsLat: $columns['gps_lat'] ?? null,
            gpsLng: $columns['gps_lng'] ?? null,
            address: $columns['user_address'] ?? null,
            city: $columns['obec_nazev'] ?? null,
            district: $columns['okres_nazev'] ?? null,
            region: $columns['kraj_nazev'] ?? null,
            country: $columns['stat'] ?? null,
            propertyType: $columns['typ_nemovitosti'] ?? null,
            propertySubType: $columns['typ_nemovitosti_u'] ?? null,
            saleType: $columns['typ_prodeje'] ?? null,
            areaLand: $columns['plocha_pozemek'] ?? null,
            areaUsable: $columns['plocha_uzitna'] ?? null,
            createdAt: $columns['datum'] ?? null,
            modifiedAt: $columns['datum_modifikace'] ?? null,
            brokerName: $broker?->jmeno ? trim((string) $broker->jmeno) : null,
            brokerEmail: $broker?->email ? trim((string) $broker->email) : null,
            brokerPhone: $broker?->mobil
                ? trim((string) $broker->mobil)
                : ($broker?->telefon ? trim((string) $broker->telefon) : null),
            images: $images,
            columns: $columns,
            mainCategory: $mainCategory,
            subCategory: $subCategory
        );
    }

    public function matchesSearch(string $query): bool
    {
        return str_contains($this->searchText, mb_strtolower($query));
    }

    public function matchesCategory(?string $main, ?string $sub): bool
    {
        if ($main && $this->mainCategory !== mb_strtolower($main)) {
            return false;
        }
        if ($sub && $this->subCategory !== mb_strtolower($sub)) {
            return false;
        }
        return true;
    }
}
