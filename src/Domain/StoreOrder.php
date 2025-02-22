<?php

declare(strict_types=1);

namespace TheNoFrameworkPetstore\Domain;

final class StoreOrder
{
    private int $id;

    private int $petId;

    private \DateTimeImmutable $shipDate;

    public function __construct(
        int $petId,
        \DateTimeImmutable $shipDate
    ) {
        $this->petId = $petId;
        $this->shipDate = $shipDate;
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

    public function getShipDate(): \DateTimeImmutable
    {
        return $this->shipDate;
    }
}
