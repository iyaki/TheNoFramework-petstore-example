<?php

declare(strict_types=1);

namespace TheNoFrameworkPetstore\Domain;

use Exception;

final readonly class PetService
{
    public function __construct(private PetRepositoryInterface $petRepository)
    {
    }

    public function findBy(?string $name = null, ?string $status = null): array
    {
        return $this->petRepository->findBy(static fn (Pet $pet): bool => (
            ($name !== null && $pet->getName() === $name)
            || ($status !== null && $pet->getStatus() === $status)
            || ($name === null && $status === null)
        ));
    }

    public function find(int $id): ?Pet
    {
        return $this->petRepository->find($id);
    }

    public function create(string $name): Pet
    {
        $pet = new Pet($name);
        $this->petRepository->add($pet);
        return $pet;
    }

    public function edit(int $id, string $name): Pet
    {
        $pet = $this->petRepository->find($id);
        if (!$pet instanceof Pet) {
            throw new Exception('Pet does not exist');
        }

        $pet->setName($name);

        return $pet;
    }

    public function remove(int $id): void
    {
        $pet = $this->petRepository->find($id);
        if (!$pet instanceof Pet) {
            throw new Exception('Pet does not exist');
        }

        $this->petRepository->remove($pet);
    }
}
