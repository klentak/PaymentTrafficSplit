<?php

declare(strict_types=1);

namespace App\Tests;

use App\App\GatewayConfiguration\View\GatewayView;
use App\App\Payment\Enum\PaymentCacheKeyEnum;
use App\App\Payment\Query\PaymentGatewayQuery;
use App\App\Payment\QueryHandler\PaymentGatewayQueryHandler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class PaymentGatewayQueryHandlerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->cache = new FilesystemAdapter();
        $cacheItem = $this->cache->getItem(PaymentCacheKeyEnum::GATEWAY_LOAD);
        $cacheItem->set([]);
        $this->cache->save($cacheItem);

        $this->paymentCommandHandler = new PaymentGatewayQueryHandler(
            $this->cache
        );
    }

    /**
     * @dataProvider App\Tests\PaymentGatewayQueryDataProvider::provide()
     */
    public function testPaymentGateways(array $gatewaysLoad, array $gatewayConfig): void
    {
        for ($i = 0; $i < 100; $i++) {
            $paymentHandler = $this->paymentCommandHandler;

            $result = $paymentHandler(new PaymentGatewayQuery(
                $gatewayConfig
            ));

            $gatewaysLoad[$result->getName()] += 1;

            $cacheItem = $this->cache->getItem(PaymentCacheKeyEnum::GATEWAY_LOAD);
            $cacheItem->set($gatewaysLoad);
            $this->cache->save($cacheItem);
        }

        $result = $this->cache->getItem(PaymentCacheKeyEnum::GATEWAY_LOAD)->get();

        /** @var GatewayView $config */
        foreach ($gatewayConfig as $config) {
            self::assertEquals(
                $config->getWeight(),
                $result[$config->getName()],
            );
        }
    }

    /**
     * @dataProvider App\Tests\PaymentGatewayQueryDataProvider::provideTestDataForSelectingGatewayWithHighestCapacity()
     */
    public function testSelectingGatewayWithHighestCapacity(array $gatewayConfig, GatewayView $expectedResult): void
    {
        $paymentHandler = $this->paymentCommandHandler;

        $result = $paymentHandler(new PaymentGatewayQuery(
            $gatewayConfig
        ));

        self::assertEquals(
            $result,
            $expectedResult,
        );
    }
}
