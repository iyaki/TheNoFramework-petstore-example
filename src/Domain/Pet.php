<?php

declare(strict_types=1);

namespace TheNoFrameworkPetstore\Domain;

final class Pet
{
    public const STATUS_AVAILABLE = 'available';

    public const STATUS_SOLD = 'sold';

    private int $id;

    private string $name;

    private string $status;

    public function __construct(string $name)
    {
        $this->id = time();
        $this->name = $name;
        $this->status = self::STATUS_AVAILABLE;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function reserve(): void
    {
        $this->status = self::STATUS_SOLD;
    }

    public function cancelReservation(): void
    {
        $this->status = self::STATUS_AVAILABLE;
    }
}
