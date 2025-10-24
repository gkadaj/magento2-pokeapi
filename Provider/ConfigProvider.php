<?php
declare(strict_types=1);

namespace Akid\PokeApi\Provider;

use Magento\Framework\App\Config\ScopeConfigInterface;

class ConfigProvider
{
    public const string XML_PATH_POKE_API_BASE_URL = 'pokeapi/general/base_url';
    public const string XML_PATH_POKE_API_ENABLED = 'pokeapi/general/enabled';

    public function __construct(private readonly ScopeConfigInterface $scopeConfig)
    {
    }

    public function getPokeAPiBaseUrl(): string
    {
        return $this->scopeConfig->getValue(self::XML_PATH_POKE_API_BASE_URL);
    }

    public function isPokeAPiEnabled(): bool
    {
        return (bool) $this->scopeConfig->getValue(self::XML_PATH_POKE_API_ENABLED);
    }
}
