<?php

declare(strict_types=1);

namespace TheNoFrameworkPetstore\Infrastructure;

use Exception;
use TheNoFrameworkPetstore\Domain\Pet;
use TheNoFrameworkPetstore\Domain\PetRepositoryInterface;

final class PetRepositoryNativeSerialization implements PetRepositoryInterface
{
    private array $pets = [];

    public function __construct(private readonly string $storeFile)
    {
        if (! file_exists($this->storeFile)) {
            file_put_contents($this->storeFile, '');
            return;
        }

        $fileData = file_get_contents($this->storeFile);
        if ($fileData === false) {
            throw new Exception("Error reading {$this->storeFile}");
        }

        $fileData = unserialize($fileData);
        if (! is_array($fileData)) {
            throw new Exception("Error unserializing the data from: {$this->storeFile}");
        }

        $this->pets = $fileData;
    }

    public function __destruct()
    {
        file_put_contents($this->storeFile, serialize($this->pets));
    }

    public function add(Pet $pet): void
    {
        $this->pets[$pet->getId()] = $pet;
    }

    public function findBy(callable $strategy): array
    {
        return array_values(array_filter($this->pets, $strategy));
    }

    public function find(int $id): ?Pet
    {
        return $this->pets[$id] ?? null;
    }

    public function remove(Pet $pet): void
    {
        unset($this->pets[$pet->getId()]);
    }
}
