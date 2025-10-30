<?php
declare(strict_types=1);

namespace Akid\PokeApi\Provider;

use Akid\PokeApi\Api\Data\GetPokemonNameProviderInterface;
use Akid\PokeApi\Api\FetchPokeServiceInterface;
use Akid\PokeApi\Exception\NoApiDataReceivedException;
use Akid\PokeApi\Sanitization\PokemonDataSanitizer;
use Akid\PokeApi\Setup\Patch\Data\AddPokemonNameAttribute;
use Magento\Catalog\Model\Product;

readonly class GetPokemonNameProvider implements GetPokemonNameProviderInterface
{
    public function __construct(
        private FetchPokeServiceInterface $pokeService,
        private PokemonDataSanitizer      $pokemonDataSanitizer
    ) {
    }

    public function execute(Product $product): string
    {
        if (!$product->getData(AddPokemonNameAttribute::POKEMON_NAME_ATTRIBUTE_CODE)) {
            return '';
        }

        $pokemon = $this->pokeService
            ->execute($product->getData(AddPokemonNameAttribute::POKEMON_NAME_ATTRIBUTE_CODE));

        if (!$pokemon || !isset($pokemon['name'])) {
            throw new NoApiDataReceivedException(__('No pokemon data received')->render());
        }

        return $this->pokemonDataSanitizer->sanitizeName($pokemon['name']);
    }
}
