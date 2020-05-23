<?php

declare(strict_types=1);

use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TheNoFramework\ApplicationWrapper;
use TheNoFrameworkPetstore\Application\HttpException;
use TheNoFrameworkPetstore\Domain\PetService;
use TheNoFrameworkPetstore\Presentation\PetJsonSerialized;

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
        $searchSegment = explode('/', $request->getUri()->getPath())[2] ?? null;
        $response = new Response();
        try {
            if (!is_numeric($searchSegment) && 'findByStatus' !== $searchSegment) {
                throw new HttpException('Invalid Request', 400);
            }
            if (is_numeric($searchSegment)) {
                $pet = $this->petService->find((int) $searchSegment);
                if (null === $pet) {
                    throw new HttpException('Pet not found', 404);
                }
                $response->getBody()->write((string) new PetJsonSerialized($pet));
            }
            if ('findByStatus' === $searchSegment) {
                $status = $request->getQueryParams()['status'] ?? null;
                if (null === $status) {
                    throw new HttpException('Invalid Request', 400);
                }
                $pets = $this->petService->findBy(null, $status);
                $response->getBody()->write((string) new PetJsonSerialized($pets));
            }
        } catch (HttpException $exception) {
            $response = $response->withStatus(0 === $exception->getCode() ? 500 : $exception->getCode());
            $response->getBody()->write($exception->getMessage());
        }
        return $response;
    }

    private function post(ServerRequestInterface $request): ResponseInterface
    {
        $requestBody = json_decode((string) $request->getBody());
        $response = new Response();
        try {
            $newPet = $this->petService->create($requestBody->name, $requestBody->status);
            $response->getBody()->write((string) new PetJsonSerialized($newPet));
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
