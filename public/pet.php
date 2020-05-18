<?php

declare(strict_types=1);

use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TheNoFramework\ApplicationWrapper;
use TheNoFrameworkPetstore\Domain\PetService;

final class PetController implements RequestHandlerInterface
{
    private PetService $petService;

    public function __construct(PetService $petService)
    {
        $this->petService = $petService;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        switch (strtoupper($request->getMethod())) {
            case 'GET':
                return $this->get($request);
            case 'POST':
                return $this->post($request);
            case 'PUT':
                return $this->put($request);
            case 'DELETE':
                return $this->delete($request);
            }
        return (new Response())->withStatus(405, 'Method not allowed');
    }

    private function get(ServerRequestInterface $request): ResponseInterface
    {
        $response = new Response();
        $response->getBody()->write('Test1'.$request->getMethod());
        return $response;
    }

    private function post(ServerRequestInterface $request): ResponseInterface
    {
        $requestBody = json_decode((string) $request->getBody());
        $response = new Response();
        try {
            $newPet = $this->petService->create($requestBody->name, $requestBody->status);
            $response->getBody()->write(
                json_encode([
                    'id' => $newPet->getId(),
                    'name' => $newPet->getName(),
                    'status' => $newPet->getStatus()
                ])
            );
        } catch (\Throwable $exception) {
            $response = $response->withStatus('400');
            $response->getBody()->write($exception->getMessage());
        }

        return $response;
    }

    private function put(ServerRequestInterface $request): ResponseInterface
    {
        $response = new Response();
        $response->getBody()->write('Test'.$request->getMethod());
        return $response;
    }

    private function delete(ServerRequestInterface $request): ResponseInterface
    {
        $response = new Response();
        $response->getBody()->write('Test'.$request->getMethod());
        return $response;
    }

}

ApplicationWrapper::run(PetController::class);