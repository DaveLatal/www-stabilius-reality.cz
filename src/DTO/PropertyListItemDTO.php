<?php

namespace App\DTO;

final readonly class PropertyListItemDTO
{
    public function __construct(
        public string $id,
        public ?string $ec,
        public ?string $modifiedAt,
    ) {}

    public static function listFromXml(string $xmlString): array
    {
        $list = [];

        $dom = new \DOMDocument();
        $dom->loadXML($xmlString, LIBXML_NOERROR | LIBXML_NOWARNING);

        $xpath = new \DOMXPath($dom);

        $propertyNodes = $xpath->query('//*[local-name()="property"]');

        foreach ($propertyNodes as $property) {
            $id = self::xpathValue($xpath, $property, './*[local-name()="id"]');

            // 💥 hard safety guard
            if ($id === null) {
                continue;
            }

            $list[] = new self(
                id: $id, // ✅ guaranteed string
                ec: self::xpathValue($xpath, $property, './*[local-name()="ec"]'),
                modifiedAt: self::xpathValue($xpath, $property, './*[local-name()="datum_modifikace"]'),
            );
        }

        return $list;
    }

    private static function xpathValue(
        \DOMXPath $xpath,
        \DOMNode $context,
        string $expr
    ): ?string {
        $nodes = $xpath->query($expr, $context);

        foreach ($nodes as $node) {
            $value = trim($node->textContent);
            if ($value !== '') {
                return $value;
            }
        }

        return null;
    }
}
