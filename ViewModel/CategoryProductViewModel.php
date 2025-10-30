<?php
declare(strict_types=1);

namespace Akid\PokeApi\ViewModel;

use Akid\PokeApi\Api\Data\GetPokemonImageUrlProviderInterface;
use Akid\PokeApi\Api\Data\GetPokemonNameProviderInterface;
use Akid\PokeApi\Api\ErrorHandlerInterface;
use Akid\PokeApi\Exception\NoApiDataReceivedException;
use Akid\PokeApi\Exception\WrongPokemonDataException;
use Akid\PokeApi\Exception\WrongPokemonPictureException;
use Magento\Catalog\Helper\Data;
use Magento\Catalog\Model\Product;
use Magento\Framework\View\Element\Block\ArgumentInterface;

readonly class CategoryProductViewModel implements ArgumentInterface
{
    public function __construct(
        private GetPokemonNameProviderInterface $getPokemonNameProvider,
        private GetPokemonImageUrlProviderInterface $getPokemonImageUrlProvider,
        private Data $catalogHelper,
        private ErrorHandlerInterface $errorHandler
    ) {
    }

    public function getProduct(): ?Product
    {
        return $this->catalogHelper->getProduct();
    }

    public function updateProductName(Product $product, string $productName): string
    {
        try {
            $pokemonName = $this->getPokemonNameProvider->execute($product);
            if ($pokemonName) {
                return sprintf("%s %s", $productName, $pokemonName);
            }
        } catch (NoApiDataReceivedException $e) {
            $this->errorHandler->handle($e);
        }

        return $productName;
    }

    public function getPokemonImageUrl(Product $product): string
    {
        try {
            return $this->getPokemonImageUrlProvider->execute($product);
        } catch (NoApiDataReceivedException|WrongPokemonDataException|WrongPokemonPictureException $e) {
            $this->errorHandler->handle($e);
        }

        return '';
    }
}
