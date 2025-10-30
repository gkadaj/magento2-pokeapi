<?php
declare(strict_types=1);

namespace Akid\PokeApi\Provider;

use Akid\PokeApi\Api\Data\GetPokemonImageUrlProviderInterface;
use Akid\PokeApi\Api\FetchPokeServiceInterface;
use Akid\PokeApi\Exception\WrongPokemonDataException;
use Akid\PokeApi\Exception\WrongPokemonPictureException;
use Akid\PokeApi\Sanitization\PokemonDataSanitizer;
use Akid\PokeApi\Setup\Patch\Data\AddPokemonNameAttribute;
use Magento\Catalog\Model\Product;

readonly class GetPokemonImageUrlProvider implements GetPokemonImageUrlProviderInterface
{
    public function __construct(
        private FetchPokeServiceInterface $pokeService,
        private PokemonDataSanitizer $pokemonDataSanitizer
    ) {
    }

    public function execute(Product $product): string
    {
        $name = $product->getData(AddPokemonNameAttribute::POKEMON_NAME_ATTRIBUTE_CODE);
        if (!$name) {
            return '';
        }

        $pokemon = $this->pokeService->execute($name);

        if (!$pokemon || !isset($pokemon['sprites']['front_default'])) {
            throw new WrongPokemonDataException(__('No pokemon data received')->render());
        }

        $pokemonUrl = $this->pokemonDataSanitizer
            ->sanitizeImageUrl($pokemon['sprites']['front_default']);

        if (!$pokemonUrl) {
            throw new WrongPokemonPictureException(__('Pokemon image url is not valid')->render());
        }

        return $pokemonUrl;
    }
}
