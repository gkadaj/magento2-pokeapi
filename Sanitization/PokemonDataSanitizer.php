<?php
declare(strict_types=1);

namespace Akid\PokeApi\Sanitization;

class PokemonDataSanitizer
{
    public function sanitizeName(string $pokemonName): string
    {
        $sanitized = strip_tags($pokemonName);
        $sanitized = htmlspecialchars($sanitized);

        return trim($sanitized);
    }

    public function sanitizeImageUrl(string $imageUrl): string
    {
        return filter_var($imageUrl, FILTER_SANITIZE_URL);
    }
}
