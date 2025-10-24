<?php
declare(strict_types=1);

namespace Akid\PokeApi\Service;

use Akid\PokeApi\Api\ErrorHandlerInterface;
use Akid\PokeApi\Api\FetchPokeServiceInterface;
use Akid\PokeApi\Connector\PokeApiConnector;
use Akid\PokeApi\Exception\NoApiDataReceivedException;
use Akid\PokeApi\Provider\ConfigProvider;
use Magento\Framework\App\CacheInterface;
use Magento\Framework\Serialize\SerializerInterface;

class FetchPokeService implements FetchPokeServiceInterface
{
    private $cacheTtl = 86400;

    public function __construct(
        private readonly PokeApiConnector $client,
        private readonly CacheInterface $cache,
        private readonly SerializerInterface $serializer,
        private readonly ConfigProvider $configProvider,
        private readonly ErrorHandlerInterface $errorHandler
    ) {
    }

    public function execute(string $pokeIdentifier): ?array
    {
        if (!$this->configProvider->isPokeAPiEnabled()) {
            return null;
        }

        $key = 'pokeapi_' . md5($pokeIdentifier);
        $cached = $this->cache->load($key);
        if ($cached) {
            return $this->serializer->unserialize($cached);
        }

        $url = $this->configProvider->getPokeAPiBaseUrl() . '/pokemon/' . strtolower($pokeIdentifier);

        try {
            $data = $this->client->get($url);
        } catch (NoApiDataReceivedException $e) {
            $this->errorHandler->handle($e);

            return null;
        }

        if ($data) {
            $this->cache->save($this->serializer->serialize($data), $key, [], $this->cacheTtl);
        }

        return $data;
    }
}
