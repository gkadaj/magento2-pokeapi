<?php
declare(strict_types=1);

namespace Akid\PokeApi\Api\Data;

use Akid\PokeApi\Exception\NoApiDataReceivedException;
use Akid\PokeApi\Exception\WrongPokemonDataException;
use Akid\PokeApi\Exception\WrongPokemonPictureException;
use Magento\Catalog\Model\Product;

interface GetPokemonImageUrlProviderInterface
{
    /**
     * @throws WrongPokemonDataException
     * @throws NoApiDataReceivedException
     * @throws WrongPokemonPictureException
     */
    public function execute(Product $product): string;
}
