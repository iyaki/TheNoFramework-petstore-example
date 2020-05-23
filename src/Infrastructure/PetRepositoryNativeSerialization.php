<?php

declare(strict_types = 1);

namespace TheNoFrameworkPetstore\Infrastructure;

use TheNoFrameworkPetstore\Domain\Pet;
use TheNoFrameworkPetstore\Domain\PetRepositoryInterface;

final class PetRepositoryNativeSerialization implements PetRepositoryInterface
{
    private string $storeFile;

    private array $pets = [];

    public function __construct(string $storeFile)
    {
        $this->storeFile = $storeFile;
        if (!file_exists($this->storeFile)) {
            file_put_contents($this->storeFile, '');
            return;
        }
        $fileData = file_get_contents($this->storeFile);
        if (false === $fileData) {
            throw new \Exception("Error reading {$this->storeFile}");
        }
        $fileData = unserialize($fileData);
        if (!is_array($fileData)) {
            throw new \Exception("Error reading the data from: {$fileData}");
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
