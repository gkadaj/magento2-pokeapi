<?php
declare(strict_types=1);

namespace Akid\PokeApi\ViewModel;

use Akid\PokeApi\Api\Data\GetPokemonImageUrlProviderInterface;
use Akid\PokeApi\Api\Data\GetPokemonNameProviderInterface;
use Magento\Catalog\Model\Product;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class CategoryProductViewModel implements ArgumentInterface
{
    public function __construct(
        private readonly GetPokemonNameProviderInterface $getPokemonNameProvider,
        private readonly GetPokemonImageUrlProviderInterface $getPokemonImageUrlProvider
    ) {
    }

    public function updateProductName(Product $product, string $productName): string
    {
        $pokemonName = $this->getPokemonNameProvider->execute($product);
        if ($pokemonName) {
            return sprintf("%s %s", $productName, $pokemonName);
        }

        return $productName;
    }

    public function getPokemonImageUrl(Product $product): string
    {
        return $this->getPokemonImageUrlProvider->execute($product);
    }
}
