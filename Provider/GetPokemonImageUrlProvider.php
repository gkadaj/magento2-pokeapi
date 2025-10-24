<?php
declare(strict_types=1);

namespace Akid\PokeApi\Provider;

use Akid\PokeApi\Api\Data\GetPokemonImageUrlProviderInterface;
use Akid\PokeApi\Api\ErrorHandlerInterface;
use Akid\PokeApi\Api\FetchPokeServiceInterface;
use Akid\PokeApi\Sanitization\PokemonDataSanitizer;
use Akid\PokeApi\Setup\Patch\Data\AddPokemonNameAttribute;
use Exception;
use Magento\Catalog\Model\Product;

class GetPokemonImageUrlProvider implements GetPokemonImageUrlProviderInterface
{
    public function __construct(
        private readonly FetchPokeServiceInterface $pokeService,
        private readonly PokemonDataSanitizer $pokemonDataSanitizer,
        private readonly ErrorHandlerInterface $errorHandler
    ) {
    }

    public function execute(Product $product): ?string
    {
        $name = $product->getData(AddPokemonNameAttribute::POKEMON_NAME_ATTRIBUTE_CODE);
        if (!$name) {
            return null;
        }

        try {
            $pokemon = $this->pokeService->execute($name);
        } catch (Exception $e) {
            $this->errorHandler->handle($e);

            return null;
        }

        if (!$pokemon || !isset($pokemon['sprites']['front_default'])) {
            $this->errorHandler->handle(new Exception('No pokemon data received'));

            return null;
        }

        $pokemonUrl = $this->pokemonDataSanitizer
            ->sanitizeImageUrl($pokemon['sprites']['front_default']);

        if (!$pokemonUrl) {
            $this->errorHandler->handle(new Exception('Pokemon image url is not valid'));

            return null;
        }

        return $pokemonUrl;
    }
}
