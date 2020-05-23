<?php

declare(strict_types = 1);

namespace TheNoFrameworkPetstore\Domain;

final class PetService
{
    private PetRepositoryInterface $petRepository;

    public function __construct(PetRepositoryInterface $petRepository)
    {
        $this->petRepository = $petRepository;
    }

    public function findBy(string $name = null, string $status = null): array
    {
        return $this->petRepository->findBy(fn (Pet $pet) => (
            (null !== $name && $pet->getName() === $name)
            || (null !== $status && $pet->getStatus() === $status)
            || (null === $name && null === $status)
        ));
    }

    public function find(int $id): ?Pet
    {
        return $this->petRepository->find($id);
    }

    public function create(string $name, string $status): Pet
    {
        $pet = new Pet($name, $status);
        $this->petRepository->add($pet);
        return $pet;
    }

    public function edit(int $id, string $name, string $status): Pet
    {
        $pet = $this->petRepository->find($id);
        if (null === $pet) {
            throw new \Exception('Pet does not exist');
        }
        $pet->setName($name);
        $pet->setStatus($status);

        return $pet;
    }

    public function remove(int $id): void
    {
        $pet = $this->petRepository->find($id);
        if (null === $pet) {
            throw new \Exception('Pet does not exist');
        }
        $this->petRepository->remove($pet);
    }
}
