<?php
declare(strict_types=1);

namespace Akid\PokeApi\Connector;

use Akid\PokeApi\Exception\NoApiDataReceivedException;
use Exception;
use Magento\Framework\HTTP\Client\Curl;
use Psr\Log\LoggerInterface;

class PokeApiConnector
{
    public function __construct(
        private readonly Curl $curl,
        private readonly LoggerInterface $logger
    ) {
    }

    /**
     * @throws NoApiDataReceivedException
     */
    public function get(string $url): ?array
    {
        try {
            $this->curl->get($url);
            if ($this->curl->getStatus() !== 200) {
                throw new NoApiDataReceivedException(
                    __("No data received. Status code: %1", $this->curl->getStatus())->render()
                );
            }

            return json_decode($this->curl->getBody(), true);
        } catch (NoApiDataReceivedException $e) {
            throw $e;
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
            return null;
        }
    }
}
