<?php

namespace App\DTO;

final readonly class PropertyDetailDTO
{
    public function __construct(
        public string $id,

        // --- Core / frequently used fields ---
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

        // --- Broker ---
        public ?string $brokerName,
        public ?string $brokerEmail,
        public ?string $brokerPhone,

        // --- Media ---
        public array $images,

        // --- Everything else (FULL XML coverage) ---
        public array $columns,
    ) {}

    public static function fromXml(string $id, \SimpleXMLElement $property): self
    {
        // 1️⃣ Parse ALL columns into a dictionary
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

        return new self(
            id: $id,

            // --- Core ---
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

            // --- Broker ---
            brokerName: $broker?->jmeno ? trim((string) $broker->jmeno) : null,
            brokerEmail: $broker?->email ? trim((string) $broker->email) : null,
            brokerPhone: $broker?->mobil
                ? trim((string) $broker->mobil)
                : ($broker?->telefon ? trim((string) $broker->telefon) : null),

            // --- Media ---
            images: $images,

            // --- FULL XML ---
            columns: $columns,
        );
    }
}
