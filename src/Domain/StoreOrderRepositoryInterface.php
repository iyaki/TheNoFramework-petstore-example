<?php

declare(strict_types = 1);

namespace TheNoFrameworkPetstore\Domain;

interface StoreOrderRepositoryInterface
{
    public function add(StoreOrder $storeOrder): void;

    public function find(int $id): ?StoreOrder;

    public function remove(StoreOrder $storeOrder): void;
}
