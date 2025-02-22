<?php

declare(strict_types=1);

namespace TheNoFrameworkPetstore\Domain;

final class StoreOrderService
{
    private StoreOrderRepositoryInterface $storeOrderRepository;

    private PetService $petService;

    public function __construct(StoreOrderRepositoryInterface $storeOrderRepository, PetService $petService)
    {
        $this->storeOrderRepository = $storeOrderRepository;
        $this->petService = $petService;
    }

    public function find(int $id): ?StoreOrder
    {
        return $this->storeOrderRepository->find($id);
    }

    public function create(int $petId, \DateTimeImmutable $shipDate): StoreOrder
    {
        $pet = $this->petService->find($petId);
        if ($pet === null) {
            throw new \Exception("The pet with id: {$petId} does not exist");
        }
        if ($pet->getStatus() !== Pet::STATUS_AVAILABLE) {
            throw new \Exception("The pet with id: {$petId} is not avilable");
        }
        if (new \DateTimeImmutable() > $shipDate) {
            throw new \Exception('Invalid ship date');
        }
        $storeOrder = new StoreOrder($petId, $shipDate);
        $pet->reserve();
        $this->storeOrderRepository->add($storeOrder);
        return $storeOrder;
    }

    public function remove(int $id): void
    {
        $storeOrder = $this->storeOrderRepository->find($id);
        if ($storeOrder === null) {
            throw new \Exception('Order does not exist');
        }
        $pet = $this->petService->find($storeOrder->getPetId());
        $this->storeOrderRepository->remove($storeOrder);
        @$pet->cancelReservation();
    }
}
