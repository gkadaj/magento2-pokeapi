<?php
declare(strict_types=1);

namespace Akid\PokeApi\Provider;

use Akid\PokeApi\Api\Data\GetPokemonNameProviderInterface;
use Akid\PokeApi\Api\FetchPokeServiceInterface;
use Akid\PokeApi\Sanitization\PokemonDataSanitizer;
use Magento\Catalog\Model\Product;

class GetPokemonNameProvider implements GetPokemonNameProviderInterface
{
    public function __construct(
        private readonly FetchPokeServiceInterface  $pokeService,
        private readonly PokemonDataSanitizer $pokemonDataSanitizer
    ) {
    }

    public function execute(Product $product): ?string
    {
        if (!$product->getData('pokemon_name')) {
            return null;
        }

        $pokemon = $this->pokeService->execute($product->getData('pokemon_name'));
        if (!$pokemon || !isset($pokemon['name'])) {
            return null;
        }

        return $this->pokemonDataSanitizer->sanitizeName($pokemon['name']);
    }
}
