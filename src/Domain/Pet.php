<?php

declare(strict_types = 1);

namespace TheNoFrameworkPetstore\Domain;

final class Pet
{
    public const STATUS_AVAILABLE = 'available';

    public const STATUS_PENDING = 'pending';

    public const STATUS_SOLD = 'sold';

    private int $id;

    private string $name;

    private string $status;

    public function __construct(string $name, string $status)
    {
        $this->name = $name;
        $this->setStatus($status);
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

    public function setStatus(string $status): void
    {
        if (
            self::STATUS_AVAILABLE !== $status
            && self::STATUS_PENDING !== $status
            && self::STATUS_SOLD !== $status
        ) {
            throw new \Exception('Invalid status. Posible status are: '.self::STATUS_AVAILABLE.', '.self::STATUS_PENDING.' and '.self::STATUS_SOLD.'.');
        }
        $this->status = $status;
    }
}
