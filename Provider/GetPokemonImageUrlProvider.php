<?php
declare(strict_types=1);

namespace Akid\PokeApi\Provider;

use Akid\PokeApi\Api\Data\GetPokemonImageUrlProviderInterface;
use Akid\PokeApi\Api\FetchPokeServiceInterface;
use Akid\PokeApi\Sanitization\PokemonDataSanitizer;
use Akid\PokeApi\Setup\Patch\Data\AddPokemonNameAttribute;
use Exception;
use Magento\Catalog\Model\Product;
use Psr\Log\LoggerInterface;

class GetPokemonImageUrlProvider implements GetPokemonImageUrlProviderInterface
{
    public function __construct(
        private readonly FetchPokeServiceInterface $pokeService,
        private readonly PokemonDataSanitizer $pokemonDataSanitizer,
        private readonly LoggerInterface $logger
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
            $this->logger->error($e->getMessage());

            return null;
        }

        if (!$pokemon || !isset($pokemon['sprites']['front_default'])) {
            $this->logger->error('No pokemon data received');

            return null;
        }

        $pokemonUrl = $this->pokemonDataSanitizer
            ->sanitizeImageUrl($pokemon['sprites']['front_default']);

        if (!$pokemonUrl) {
            $this->logger->error('Pokemon image url is not valid');

            return null;
        }

        return $pokemonUrl;
    }
}
