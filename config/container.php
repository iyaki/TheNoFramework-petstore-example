<?php

declare(strict_types=1);

use League\Container\Container;
use TheNoFrameworkPetstore\Application\AuthMiddleware;
use TheNoFrameworkPetstore\Domain\PetRepositoryInterface;
use TheNoFrameworkPetstore\Domain\PetService;
use TheNoFrameworkPetstore\Domain\StoreOrderRepositoryInterface;
use TheNoFrameworkPetstore\Domain\StoreOrderService;
use TheNoFrameworkPetstore\Infrastructure\PetRepositoryNativeSerialization;
use TheNoFrameworkPetstore\Infrastructure\StoreOrderRepositoryNativeSerialization;

$container = new Container();

$container->add(PetController::class)->addArgument(PetService::class);
$container->add(PetService::class)->addArgument(PetRepositoryInterface::class);
$container->add(PetRepositoryInterface::class, PetRepositoryNativeSerialization::class)->addArgument(__DIR__ . '/../storage/pets.serialized');

$container->add(StoreOrderController::class)->addArgument(StoreOrderService::class);
$container->add(StoreOrderService::class)
    ->addArgument(StoreOrderRepositoryInterface::class)
    ->addArgument(PetService::class)
;
$container->add(StoreOrderRepositoryInterface::class, StoreOrderRepositoryNativeSerialization::class)->addArgument(__DIR__ . '/../storage/store_order.serialized');

$container->add(AuthMiddleware::class);

return $container;
