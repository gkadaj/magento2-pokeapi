<?php
declare(strict_types=1);

namespace Akid\PokeApi\Api;

use Throwable;

interface ErrorHandlerInterface
{
    public function handle(Throwable $e): void;
}
