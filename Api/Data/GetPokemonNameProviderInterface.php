<?php
declare(strict_types=1);

namespace Akid\PokeApi\Api\Data;

use Akid\PokeApi\Exception\NoApiDataReceivedException;
use Magento\Catalog\Model\Product;

interface GetPokemonNameProviderInterface
{
    /**
     * @throws NoApiDataReceivedException
     */
    public function execute(Product $product): ?string;
}
