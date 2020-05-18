<?php

declare(strict_types = 1);

use TheNoFrameworkPetstore\Domain\PetRepositoryInterface;
use TheNoFrameworkPetstore\Domain\PetService;
use TheNoFrameworkPetstore\Infrastructure\PetRepositoryNativeSerialization;

$container = new League\Container\Container();

$container->add(PetController::class)->addArgument(PetService::class);
$container->add(PetService::class)->addArgument(PetRepositoryInterface::class);
$container->add(PetRepositoryInterface::class, PetRepositoryNativeSerialization::class)->addArgument('/application/storage/pets.serialized');

return $container;
