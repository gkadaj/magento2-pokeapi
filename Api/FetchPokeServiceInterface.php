<?php
declare(strict_types=1);

namespace Akid\PokeApi\Api;

interface FetchPokeServiceInterface
{
    public function execute(string $pokeIdentifier): ?array;
}
