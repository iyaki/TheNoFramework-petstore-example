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

    public function find(): array
    {
        return [];
    }

    public function create(string $name, string $status): Pet
    {
        $pet = new Pet($name, $status);
        $this->petRepository->add($pet);
        return $pet;
    }
}
