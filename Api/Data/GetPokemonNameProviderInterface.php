<?php
declare(strict_types=1);

namespace Akid\PokeApi\Api\Data;

use Magento\Catalog\Model\Product;

interface GetPokemonNameProviderInterface
{
    public function execute(Product $product): ?string;
}
