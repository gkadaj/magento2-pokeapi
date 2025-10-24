<?php
declare(strict_types=1);

namespace Akid\PokeApi\Api\Data;

use Magento\Catalog\Model\Product;

interface GetPokemonImageUrlProviderInterface
{
    public function execute(Product $product): ?string;
}
