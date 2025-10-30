<?php
declare(strict_types=1);

namespace Akid\PokeApi\Test\Unit\Provider;

use Akid\PokeApi\Api\FetchPokeServiceInterface;
use Akid\PokeApi\Provider\GetPokemonImageUrlProvider;
use Akid\PokeApi\Sanitization\PokemonDataSanitizer;
use Magento\Catalog\Model\Product;
use PHPUnit\Framework\TestCase;

class GetPokemonImageUrlProviderTest extends TestCase
{
    private const string POKEMON_PICTURE_URL = 'https://cdn.sstatic.net/Img/teams/teams-promo.svg';

    public function testCorrectApiResponse()
    {
        $fetchPokeServiceStub = $this->createStub(FetchPokeServiceInterface::class);
        $fetchPokeServiceStub->method('execute')->willReturn(
            ['sprites' => ['front_default' => self::POKEMON_PICTURE_URL]]
        );
        $productStub = $this->createStub(Product::class);
        $productStub->method('getData')->willReturn('not_empty_string');

        $sut = new GetPokemonImageUrlProvider(
            pokeService: $fetchPokeServiceStub,
            pokemonDataSanitizer: new PokemonDataSanitizer
        );

        $result = $sut->execute($productStub);

        $this->assertSame(self::POKEMON_PICTURE_URL, $result);
    }
}
