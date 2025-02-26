<?php

declare(strict_types=1);

namespace TheNoFrameworkPetstore\Infrastructure;

use Exception;
use TheNoFrameworkPetstore\Domain\StoreOrder;
use TheNoFrameworkPetstore\Domain\StoreOrderRepositoryInterface;

final class StoreOrderRepositoryNativeSerialization implements StoreOrderRepositoryInterface
{
    private array $storeOrders = [];

    public function __construct(private readonly string $storeFile)
    {
        if (! file_exists($this->storeFile)) {
            file_put_contents($this->storeFile, '');
            return;
        }

        $fileData = file_get_contents($this->storeFile);
        if ($fileData === false) {
            throw new Exception("Error reading {$this->storeFile}");
        }

        $fileData = unserialize($fileData);
        if (! is_array($fileData)) {
            throw new Exception("Error unserializing the data from: {$this->storeFile}");
        }

        $this->storeOrders = $fileData;
    }

    public function __destruct()
    {
        file_put_contents($this->storeFile, serialize($this->storeOrders));
    }

    public function add(StoreOrder $storeOrder): void
    {
        $this->storeOrders[$storeOrder->getId()] = $storeOrder;
    }

    public function find(int $id): ?StoreOrder
    {
        return $this->storeOrders[$id] ?? null;
    }

    public function remove(StoreOrder $storeOrder): void
    {
        unset($this->storeOrders[$storeOrder->getId()]);
    }
}
