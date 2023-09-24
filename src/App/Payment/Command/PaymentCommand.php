<?php

declare(strict_types=1);

namespace App\App\Payment\Command;

use App\App\GatewayConfiguration\View\GatewayView;
use App\App\Shared\CQRS\Command\Command;

readonly class PaymentCommand implements Command
{
    public function __construct(
        private GatewayView $paymentGateway,
        private array $gatewayConfiguration,
        private string $payerUUID,
        private int $amount,
    ) {
    }

    public function getGatewayConfiguration(): array
    {
        return $this->gatewayConfiguration;
    }

    public function getPaymentGateway(): GatewayView
    {
        return $this->paymentGateway;
    }

    public function getPayerUUID(): string
    {
        return $this->payerUUID;
    }

    public function getAmount(): string
    {
        return $this->amount;
    }
}
