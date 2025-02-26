<?php

declare(strict_types=1);

namespace TheNoFrameworkPetstore\Presentation;

use Stringable;
use DateTimeInterface;
use TheNoFrameworkPetstore\Domain\StoreOrder;

final class StoreOrderJsonSerialized implements Stringable
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

    public function __toString(): string
    {
        return (string) $this->data;
    }

    private function serializePet(StoreOrder $pet)
    {
        return [
            'id' => $pet->getId(),
            'petId' => $pet->getPetId(),
            'shipDate' => $pet->getShipDate()->format(DateTimeInterface::ATOM),
        ];
    }
}
