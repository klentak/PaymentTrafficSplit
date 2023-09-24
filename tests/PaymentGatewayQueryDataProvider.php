<?php

declare(strict_types=1);

namespace App\Tests;

use App\App\GatewayConfiguration\View\GatewayView;
use Generator;

class PaymentGatewayQueryDataProvider
{
    public function provide(): Generator
    {
        yield [
            [
                'Gateway1' => 0,
                'Gateway2' => 0,
                'Gateway3' => 0,
                'Gateway4' => 0,
            ],
            [
                new GatewayView(1, 'Gateway1', 25),
                new GatewayView(1, 'Gateway2', 25),
                new GatewayView(1, 'Gateway3', 25),
                new GatewayView(1, 'Gateway4', 25),
            ]
        ];
        yield [
            [
                'Gateway1' => 0,
                'Gateway2' => 0,
                'Gateway3' => 0,
            ],
            [
                new GatewayView(1, 'Gateway1', 75),
                new GatewayView(1, 'Gateway2', 10),
                new GatewayView(1, 'Gateway3', 15),
            ]
        ];
    }

    public function provideTestDataForSelectingGatewayWithHighestCapacity(): Generator
    {
        yield [
            [
                new GatewayView(1, 'Gateway1', 75),
                new GatewayView(1, 'Gateway2', 10),
                new GatewayView(1, 'Gateway3', 15),
            ],
            new GatewayView(1, 'Gateway1', 75),
        ];

        yield [
            [
                new GatewayView(1, 'Gateway1', 10),
                new GatewayView(1, 'Gateway2', 80),
                new GatewayView(1, 'Gateway3', 10),
            ],
            new GatewayView(1, 'Gateway2', 80),
        ];
    }
}
