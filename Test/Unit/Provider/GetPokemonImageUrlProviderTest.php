<?php
declare(strict_types=1);

namespace Akid\PokeApi\Test\Unit\Provider;

use Akid\PokeApi\Api\FetchPokeServiceInterface;
use Akid\PokeApi\Exception\WrongPokemonDataException;
use Akid\PokeApi\Exception\WrongPokemonPictureException;
use Akid\PokeApi\Provider\GetPokemonImageUrlProvider;
use Akid\PokeApi\Sanitization\PokemonDataSanitizer;
use Magento\Catalog\Model\Product;
use PHPUnit\Framework\TestCase;

class GetPokemonImageUrlProviderTest extends TestCase
{
    private const string POKEMON_PICTURE_URL = 'https://cdn.sstatic.net/Img/teams/teams-promo.svg';

    public function testUknownStructureApiResponse()
    {
        $fetchPokeServiceStub = $this->createStub(FetchPokeServiceInterface::class);
        $fetchPokeServiceStub->method('execute')->willReturn(
            ['sprites' => ['url' => self::POKEMON_PICTURE_URL]]
        );
        $productStub = $this->createStub(Product::class);
        $productStub->method('getData')->willReturn('not_empty_string');

        $sut = new GetPokemonImageUrlProvider(
            pokeService: $fetchPokeServiceStub,
            pokemonDataSanitizer: new PokemonDataSanitizer
        );

        $this->expectException(WrongPokemonDataException::class);
        $sut->execute($productStub);
    }

    public function testWrongPokemonPictureUrl()
    {
        $fetchPokeServiceStub = $this->createStub(FetchPokeServiceInterface::class);
        $fetchPokeServiceStub->method('execute')->willReturn(
            ['sprites' => ['front_default' => self::POKEMON_PICTURE_URL]]
        );
        $productStub = $this->createStub(Product::class);
        $productStub->method('getData')->willReturn('not_empty_string');
        $pokemonDataSanitizerStub = $this->createStub(PokemonDataSanitizer::class);
        $pokemonDataSanitizerStub->method('sanitizeImageUrl')->willReturn('');

        $sut = new GetPokemonImageUrlProvider(
            pokeService: $fetchPokeServiceStub,
            pokemonDataSanitizer: $pokemonDataSanitizerStub
        );

        $this->expectException(WrongPokemonPictureException::class);
        $sut->execute($productStub);
    }

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

    public function testProductWithoutPokemonAttribute()
    {
        $fetchPokeServiceStub = $this->createStub(FetchPokeServiceInterface::class);
        $fetchPokeServiceStub->method('execute')->willReturn(
            ['sprites' => ['front_default' => self::POKEMON_PICTURE_URL]]
        );
        $productStub = $this->createStub(Product::class);
        $productStub->method('getData')->willReturn(null);

        $sut = new GetPokemonImageUrlProvider(
            pokeService: $fetchPokeServiceStub,
            pokemonDataSanitizer: new PokemonDataSanitizer
        );

        $result = $sut->execute($productStub);

        $this->assertEmpty($result);
    }
}
