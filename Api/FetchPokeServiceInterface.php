<?php
declare(strict_types=1);

namespace Akid\PokeApi\Api;

use Akid\PokeApi\Exception\NoApiDataReceivedException;

interface FetchPokeServiceInterface
{
    /**
     * @throws NoApiDataReceivedException
     */
    public function execute(string $pokeIdentifier): ?array;
}
