<?php
declare(strict_types=1);

namespace Akid\PokeApi\Provider;

use Akid\PokeApi\Api\Data\GetPokemonNameProviderInterface;
use Akid\PokeApi\Api\FetchPokeServiceInterface;
use Akid\PokeApi\Sanitization\PokemonDataSanitizer;
use Akid\PokeApi\Setup\Patch\Data\AddPokemonNameAttribute;
use Exception;
use Magento\Catalog\Model\Product;
use Psr\Log\LoggerInterface;

class GetPokemonNameProvider implements GetPokemonNameProviderInterface
{
    public function __construct(
        private readonly FetchPokeServiceInterface  $pokeService,
        private readonly PokemonDataSanitizer $pokemonDataSanitizer,
        private readonly LoggerInterface $logger
    ) {
    }

    public function execute(Product $product): ?string
    {
        if (!$product->getData(AddPokemonNameAttribute::POKEMON_NAME_ATTRIBUTE_CODE)) {
            return null;
        }

        try {
            $pokemon = $this->pokeService
                ->execute($product->getData(AddPokemonNameAttribute::POKEMON_NAME_ATTRIBUTE_CODE));
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());

            return null;
        }

        if (!$pokemon || !isset($pokemon['name'])) {
            $this->logger->error('No pokemon data received');

            return null;
        }

        return $this->pokemonDataSanitizer->sanitizeName($pokemon['name']);
    }
}
