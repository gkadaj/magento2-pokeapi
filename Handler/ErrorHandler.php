<?php
declare(strict_types=1);

namespace Akid\PokeApi\Handler;

use Akid\PokeApi\Api\ErrorHandlerInterface;
use Magento\Framework\Message\ManagerInterface;
use Psr\Log\LoggerInterface;

class ErrorHandler implements ErrorHandlerInterface
{
    public function __construct(
        private readonly ManagerInterface $messageManager,
        private readonly LoggerInterface $logger
    ) {
    }

    public function handle(\Throwable $e): void
    {
        $this->messageManager->addErrorMessage($e->getMessage());
        $this->logger->error($e->getMessage());
    }
}
