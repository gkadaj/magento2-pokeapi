<?php
declare(strict_types=1);

namespace Akid\PokeApi\Provider;

use Akid\PokeApi\Api\Data\GetPokemonImageUrlProviderInterface;
use Akid\PokeApi\Api\FetchPokeServiceInterface;
use Akid\PokeApi\Sanitization\PokemonDataSanitizer;
use Magento\Catalog\Model\Product;

class GetPokemonImageUrlProvider implements GetPokemonImageUrlProviderInterface
{
    public function __construct(
        private readonly FetchPokeServiceInterface $pokeService,
        private readonly PokemonDataSanitizer $pokemonDataSanitizer
    ) {
    }

    public function execute(Product $product): ?string
    {
        $name = $product->getData('pokemon_name');
        if (!$name) {
            return null;
        }

        $pokemon = $this->pokeService->execute($name);
        if (!$pokemon || !isset($pokemon['sprites']['front_default'])) {
            return null;
        }

        $pokemonUrl = $this->pokemonDataSanitizer
            ->sanitizeImageUrl($pokemon['sprites']['front_default']);

        if (!$pokemonUrl) {
            return null;
        }

        return $pokemonUrl;
    }
}
