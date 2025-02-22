<?php

declare(strict_types=1);

use TheNoFrameworkPetstore\Application\AuthMiddleware;
use TheNoFrameworkPetstore\Domain\PetRepositoryInterface;
use TheNoFrameworkPetstore\Domain\PetService;
use TheNoFrameworkPetstore\Domain\StoreOrderRepositoryInterface;
use TheNoFrameworkPetstore\Domain\StoreOrderService;
use TheNoFrameworkPetstore\Infrastructure\PetRepositoryNativeSerialization;
use TheNoFrameworkPetstore\Infrastructure\StoreOrderRepositoryNativeSerialization;

$container = new League\Container\Container();

$container->add(PetController::class)->addArgument(PetService::class);
$container->add(PetService::class)->addArgument(PetRepositoryInterface::class);
$container->add(PetRepositoryInterface::class, PetRepositoryNativeSerialization::class)->addArgument('/application/storage/pets.serialized');

$container->add(StoreOrderController::class)->addArgument(StoreOrderService::class);
$container->add(StoreOrderService::class)
    ->addArgument(StoreOrderRepositoryInterface::class)
    ->addArgument(PetService::class)
;
$container->add(StoreOrderRepositoryInterface::class, StoreOrderRepositoryNativeSerialization::class)->addArgument('/application/storage/store_order.serialized');

$container->add(AuthMiddleware::class);

return $container;
