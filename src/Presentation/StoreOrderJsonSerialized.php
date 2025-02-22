<?php

declare(strict_types=1);

namespace TheNoFrameworkPetstore\Presentation;

use TheNoFrameworkPetstore\Domain\StoreOrder;

final class StoreOrderJsonSerialized
{
    private $data;

    public function __construct($orders)
    {
        if (! is_array($orders)) {
            $orders = [$orders];
        }
        $this->data = json_encode(
            array_map(
                [$this, 'serializePet'],
                $orders
            )
        );
    }

    public function __toString()
    {
        return $this->data;
    }

    private function serializePet(StoreOrder $pet)
    {
        return [
            'id' => $pet->getId(),
            'petId' => $pet->getPetId(),
            'shipDate' => $pet->getShipDate()->format(\DateTimeInterface::ATOM),
        ];
    }
}
