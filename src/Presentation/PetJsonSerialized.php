<?php

declare(strict_types = 1);

namespace TheNoFrameworkPetstore\Presentation;

use TheNoFrameworkPetstore\Domain\Pet;

final class PetJsonSerialized
{
    private $data;

    public function __construct($pets)
    {
        if (!is_array($pets)) {
            $pets = [$pets];
        }
        $this->data = json_encode(
            array_map(
                [$this, 'serializePet'],
                $pets
            )
        );
    }

    public function __toString()
    {
        return $this->data;
    }

    private function serializePet(Pet $pet)
    {
        return [
            'id' => $pet->getId(),
            'name' => $pet->getName(),
            'status' => $pet->getStatus(),
        ];
    }
}
