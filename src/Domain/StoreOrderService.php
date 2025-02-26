<?php

declare(strict_types=1);

namespace TheNoFrameworkPetstore\Domain;

use DateTimeImmutable;
use Exception;

final readonly class StoreOrderService
{
    public function __construct(private StoreOrderRepositoryInterface $storeOrderRepository, private PetService $petService)
    {
    }

    public function find(int $id): ?StoreOrder
    {
        return $this->storeOrderRepository->find($id);
    }

    public function create(int $petId, DateTimeImmutable $shipDate): StoreOrder
    {
        $pet = $this->petService->find($petId);
        if (!$pet instanceof Pet) {
            throw new Exception("The pet with id: {$petId} does not exist");
        }

        if ($pet->getStatus() !== Pet::STATUS_AVAILABLE) {
            throw new Exception("The pet with id: {$petId} is not avilable");
        }

        if (new DateTimeImmutable() > $shipDate) {
            throw new Exception('Invalid ship date');
        }

        $storeOrder = new StoreOrder($petId, $shipDate);
        $pet->reserve();
        $this->storeOrderRepository->add($storeOrder);
        return $storeOrder;
    }

    public function remove(int $id): void
    {
        $storeOrder = $this->storeOrderRepository->find($id);
        if (!$storeOrder instanceof StoreOrder) {
            throw new Exception('Order does not exist');
        }

        $pet = $this->petService->find($storeOrder->getPetId());
        $this->storeOrderRepository->remove($storeOrder);
        @$pet->cancelReservation();
    }
}
