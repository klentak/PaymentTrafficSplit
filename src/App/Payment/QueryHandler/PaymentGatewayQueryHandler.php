<?php

declare(strict_types=1);

namespace App\App\Payment\QueryHandler;

use App\App\GatewayConfiguration\View\GatewayView;
use App\App\Payment\Enum\PaymentCacheKeyEnum;
use App\App\Payment\Query\PaymentGatewayQuery;
use App\App\Shared\CQRS\Query\QueryHandler;
use Symfony\Contracts\Cache\CacheInterface;

class PaymentGatewayQueryHandler implements QueryHandler
{
    public function __construct(
        private readonly CacheInterface $cache,
    ) {
    }

    public function __invoke(PaymentGatewayQuery $query): GatewayView
    {
        $gatewaysLoad = $this->cache->getItem(PaymentCacheKeyEnum::GATEWAY_LOAD)->get();

        if (empty($gatewaysLoad)) {
            return $this->findGatewayWithHighestCapacity($query->getGatewaysConfiguration());
        }

        return $this->getGateway(
            $gatewaysLoad,
            $query->getGatewaysConfiguration()
        );
    }

    private function getGateway(array $gatewaysLoad, array $gatewaysConfiguration): GatewayView
    {
        $gatewaysWithTheMostFreeResources = $this->getGatewaysWithTheMostFreeResources(
            $gatewaysLoad,
            $gatewaysConfiguration
        );

        return $this->findGatewayWithHighestCapacity($gatewaysWithTheMostFreeResources);
    }

    private function findGatewayWithHighestCapacity(array $gatewaysConfigurations): GatewayView
    {
        $result = null;

        /** @var GatewayView $gatewaysConfiguration */
        foreach ($gatewaysConfigurations as $gatewaysConfiguration) {
            if (
                !$result instanceof GatewayView
                || $result->getWeight() < $gatewaysConfiguration->getWeight()
            ) {
                $result = $gatewaysConfiguration;
            }
        }

        return $result;
    }

    private function getGatewaysWithTheMostFreeResources(array $gatewaysLoad, array $gatewaysConfiguration): array
    {
        $numberOfPayments = array_sum($gatewaysLoad);
        $result = [];

        /** @var GatewayView $gatewayConfiguration */
        foreach ($gatewaysConfiguration as $gatewayConfiguration) {
            $percentageLoad = $gatewaysLoad[$gatewayConfiguration->getName()] * 100 / $numberOfPayments;
            $result[$gatewayConfiguration->getName()] = $gatewayConfiguration->getWeight() - $percentageLoad;
        }

        $gatewaysNames = array_keys($result, max($result));

        return $this->findGatewaysByNames($gatewaysNames, $gatewaysConfiguration);
    }

    private function findGatewaysByNames(array $gatewaysNames, array $gatewaysConfiguration): array
    {
        $gateways = [];

        /** @var GatewayView $gatewayConfiguration */
        foreach ($gatewaysConfiguration as $gatewayConfiguration) {
            if (in_array($gatewayConfiguration->getName(), $gatewaysNames, true)) {
                $gateways[] = $gatewayConfiguration;
            }
        }

        return $gateways;
    }
}
