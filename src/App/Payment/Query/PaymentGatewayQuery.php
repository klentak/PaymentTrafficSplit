<?php

declare(strict_types=1);

namespace App\App\Payment\Query;

use App\App\Shared\CQRS\Query\Query;

readonly class PaymentGatewayQuery implements Query
{
    public function __construct(
        private array $gatewaysConfiguration
    ) {
    }

    public function getGatewaysConfiguration(): array
    {
        return $this->gatewaysConfiguration;
    }
}
