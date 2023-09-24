<?php

declare(strict_types=1);

namespace App\UI;

use App\App\GatewayConfiguration\Query\AllPaymentGatewaysConfigurationQuery;
use App\App\Payment\Command\PaymentCommand;
use App\App\Payment\Enum\PaymentResponseMessageEnum;
use App\App\Payment\Query\PaymentGatewayQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/payment', name: 'payment.')]
class PaymentController extends AbstractController
{
    public function __construct(
        private readonly MessageBusInterface $queryBus,
        private readonly MessageBusInterface $commandBus,
    ) {
    }

    #[Route('', name: 'accept', methods: [Request::METHOD_POST])]
    public function acceptPayment(Request $request): JsonResponse
    {
        $data = $request->toArray();

        $gatewaysConfiguration = $this->queryBus->dispatch(
            new AllPaymentGatewaysConfigurationQuery()
        )->last(HandledStamp::class)->getResult();

        $gateway = $this->queryBus->dispatch(
            new PaymentGatewayQuery($gatewaysConfiguration)
        )->last(HandledStamp::class)->getResult();

        $this->commandBus->dispatch(
            new PaymentCommand(
                $gateway,
                $gatewaysConfiguration,
                $data['payerUUID'],
                $data['amount'],
            )
        );


        return new JsonResponse([
            'message' => PaymentResponseMessageEnum::ACCEPTED_MESSAGE,
            'selectedGateway' => $gateway->getName()
        ]);
    }
}
