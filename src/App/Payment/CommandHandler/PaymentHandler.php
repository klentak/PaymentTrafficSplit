<?php

declare(strict_types=1);

namespace App\App\Payment\CommandHandler;

use App\App\GatewayConfiguration\View\GatewayView;
use App\App\Payment\Command\PaymentCommand;
use App\App\Payment\Enum\PaymentCacheKeyEnum;
use App\App\Shared\CQRS\Command\CommandHandler;
use Symfony\Component\Cache\CacheItem;
use Symfony\Contracts\Cache\CacheInterface;

class PaymentHandler implements CommandHandler
{
    public function __construct(
        private readonly CacheInterface $cache,
    ) {
    }

    public function __invoke(PaymentCommand $command): void
    {
        $this->processPayment($command);

        $this->updateGatewaysLoad(
            $command->getPaymentGateway(),
            $command->getGatewayConfiguration()
        );
    }

    private function processPayment(PaymentCommand $command): void
    {
        // TODO: Implement payment process
    }

    private function updateGatewaysLoad(
        GatewayView $gateway,
        array $gatewaysConfig,
    ): void {
        /** @var CacheItem $gatewaysLoadCache */
        $gatewaysLoadCache = $this->cache->getItem(PaymentCacheKeyEnum::GATEWAY_LOAD);

        if (!$gatewaysLoadCache->isHit()) {
            $this->setUpCacheGatewaysLoad(
                $gatewaysLoadCache,
                $gateway,
                $gatewaysConfig
            );
        }

        $gatewaysLoad = $gatewaysLoadCache->get();
        $gatewaysLoad[$gateway->getName()] += 1;
        $gatewaysLoadCache->set($gatewaysLoad);

        $this->cache->save($gatewaysLoadCache);
    }

    private function setUpCacheGatewaysLoad(
        CacheItem $gatewaysLoadCache,
        GatewayView $gateway,
        array $gatewaysConfig
    ) {
        $gatewaysLoad = [];

        /** @var GatewayView $gateway */
        foreach ($gatewaysConfig as $gatewayConfig) {
            $gatewaysLoad[$gatewayConfig->getName()] = 0;
        }

        $gatewaysLoad[$gateway->getName()] += 1;

        $gatewaysLoadCache->set($gatewaysLoad);
        $this->cache->save($gatewaysLoadCache);
    }
}
