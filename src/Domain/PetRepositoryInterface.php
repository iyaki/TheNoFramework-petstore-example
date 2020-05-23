<?php

declare(strict_types = 1);

namespace TheNoFrameworkPetstore\Domain;

interface PetRepositoryInterface
{
    public function add(Pet $pet): void;

    public function findBy(callable $strategy): array;

    public function find(int $id): ?Pet;
}
