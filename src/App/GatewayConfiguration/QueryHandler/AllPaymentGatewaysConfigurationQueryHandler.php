<?php

declare(strict_types=1);

namespace App\App\GatewayConfiguration\QueryHandler;

use App\App\GatewayConfiguration\Query\AllPaymentGatewaysConfigurationQuery;
use App\App\GatewayConfiguration\Repository\GatewayConfigurationRepository;
use App\App\GatewayConfiguration\View\GatewayView;
use App\App\GatewayConfiguration\Enum\GatewayConfigurationCacheKeyEnum;
use App\App\Shared\CQRS\Query\QueryHandler;
use RuntimeException;
use Symfony\Contracts\Cache\CacheInterface;

class AllPaymentGatewaysConfigurationQueryHandler implements QueryHandler
{
    public function __construct(
        private readonly GatewayConfigurationRepository $gatewayConfigurationRepository,
        private readonly CacheInterface $cache,
    ) {
    }

    public function __invoke(AllPaymentGatewaysConfigurationQuery $query): array
    {
        $gatewayConfigCache = $this->cache->getItem(GatewayConfigurationCacheKeyEnum::GATEWAY_CONFIG);

        if ($gatewayConfigCache->isHit()) {
            return $gatewayConfigCache->get();
        }

        $result = $this->gatewayConfigurationRepository->getAll();

        $this->validateConfig($result);

        $gatewayConfigCache->set($result);
        $this->cache->save($gatewayConfigCache);

        return $result;
    }

    private function validateConfig(array $gatewayConfig): void
    {
        $sum = 0;

        /** @var GatewayView $gateway */
        foreach ($gatewayConfig as $gateway) {
            $sum += $gateway->getWeight();
        }

        if ($sum !== 100) {
            throw new RuntimeException('Invalid gateways config');
        }
    }
}
