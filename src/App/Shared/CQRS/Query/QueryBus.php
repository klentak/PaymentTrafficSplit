<?php

declare(strict_types=1);

namespace App\App\Shared\CQRS\Query;

interface QueryBus
{
    public function dispatch(Query $query): mixed;
}
