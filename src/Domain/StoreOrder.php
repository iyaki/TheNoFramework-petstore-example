<?php

declare(strict_types=1);

namespace TheNoFrameworkPetstore\Domain;

use DateTimeImmutable;

final readonly class StoreOrder
{
    private int $id;

    public function __construct(
        private int $petId,
        private DateTimeImmutable $shipDate
    ) {
        $this->id = time();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getPetId(): int
    {
        return $this->petId;
    }

    public function getShipDate(): DateTimeImmutable
    {
        return $this->shipDate;
    }
}
