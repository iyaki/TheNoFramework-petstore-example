<?php

declare(strict_types=1);

namespace TheNoFrameworkPetstore\Domain;

final class Pet
{
    public const string STATUS_AVAILABLE = 'available';

    public const string STATUS_SOLD = 'sold';

    private readonly int $id;

    private string $status = self::STATUS_AVAILABLE;

    public function __construct(private string $name)
    {
        $this->id = time();
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
