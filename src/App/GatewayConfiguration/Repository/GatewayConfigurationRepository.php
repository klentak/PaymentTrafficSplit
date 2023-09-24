<?php

declare(strict_types=1);

namespace App\App\GatewayConfiguration\Repository;

use App\App\GatewayConfiguration\View\GatewayView;
use Doctrine\DBAL\Connection;

class GatewayConfigurationRepository
{
    public function __construct(
        private readonly Connection $connection,
    ) {
    }

    public function getAll(): array
    {
        $queryBuilder = $this->connection->createQueryBuilder()
            ->select(['g.id', 'g.name', 'g.weight'])
            ->from('gateway_configuration', 'g');

        $bookData = $this->connection->fetchAllAssociative(
            $queryBuilder->getSQL(),
            $queryBuilder->getParameters()
        );

        return array_map(function(array $bookData): GatewayView {
            return new GatewayView(
                $bookData['id'],
                $bookData['name'],
                $bookData['weight'],
            );
        }, $bookData);

    }
}
