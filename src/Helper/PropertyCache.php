<?php

namespace App\Helper;

final class PropertyCache
{
    private const TTL = 600; // 10 minutes
    private const FILE = __DIR__ . '/properties.cache.php';

    public static function get(callable $fetcher): array
    {
        if (file_exists(self::FILE)) {
            $data = include self::FILE;
            if ($data['expires'] > time()) {
                return $data['items'];
            }
        }

        $items = $fetcher();

        file_put_contents(
            self::FILE,
            '<?php return ' . var_export([
                'expires' => time() + self::TTL,
                'items' => $items,
            ], true) . ';'
        );

        return $items;
    }
}
