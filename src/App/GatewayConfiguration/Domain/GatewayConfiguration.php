<?php

declare(strict_types=1);

namespace App\App\GatewayConfiguration\Domain;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Symfony\Contracts\Service\Attribute\Required;

#[Entity]
class GatewayConfiguration
{
    #[Id]
    #[Column(type: 'integer', nullable: false)]
    #[GeneratedValue(strategy: 'SEQUENCE')]
    private ?int $id;

    #[Required]
    #[Column(type: 'string', length: 255, unique: true)]
    private string $name;

    #[Required]
    #[Column(type: 'integer', unique: false)]
    private string $weight;

    public function __construct(
        string $name,
        string $weight,
        ?int $id = null,
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->weight = $weight;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getWeight(): string
    {
        return $this->weight;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setWeight(string $weight): void
    {
        $this->weight = $weight;
    }
}
