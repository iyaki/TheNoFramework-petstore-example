<?php

declare(strict_types=1);

use TheNoFrameworkPetstore\Domain\Pet;
use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TheNoFramework\ApplicationWrapper;
use TheNoFrameworkPetstore\Application\AuthMiddleware;
use TheNoFrameworkPetstore\Application\HttpException;
use TheNoFrameworkPetstore\Domain\PetService;
use TheNoFrameworkPetstore\Presentation\PetJsonSerialized;

final readonly class PetController implements RequestHandlerInterface
{
    public function __construct(private PetService $petService)
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return match (strtoupper($request->getMethod())) {
            'GET' => $this->get($request),
            'POST' => $this->post($request),
            'PUT' => $this->put($request),
            'DELETE' => $this->delete($request),
            default => (new Response())->withStatus(405, 'Method not allowed'),
        };
    }

    private function get(ServerRequestInterface $request): ResponseInterface
    {
        $searchSegment = explode('/', $request->getUri()->getPath())[2] ?? null;
        $response = new Response();
        try {
            if (! is_numeric($searchSegment) && $searchSegment !== 'findByStatus') {
                throw new HttpException('Invalid Request', 400);
            }

            if (is_numeric($searchSegment)) {
                $pet = $this->petService->find((int) $searchSegment);
                if (!$pet instanceof Pet) {
                    throw new HttpException('Pet not found', 404);
                }

                $response->getBody()->write((string) new PetJsonSerialized($pet));
            }

            if ($searchSegment === 'findByStatus') {
                $status = $request->getQueryParams()['status'] ?? null;
                if ($status === null) {
                    throw new HttpException('Invalid Request', 400);
                }

                $pets = $this->petService->findBy(null, $status);
                $response->getBody()->write((string) new PetJsonSerialized($pets));
            }
        } catch (HttpException $httpException) {
            $response = $response->withStatus($httpException->getCode() === 0 ? 500 : $httpException->getCode());
            $response->getBody()->write($httpException->getMessage());
        }

        return $response;
    }

    private function post(ServerRequestInterface $request): ResponseInterface
    {
        $requestBody = json_decode((string) $request->getBody());
        $response = new Response();
        try {
            $newPet = $this->petService->create($requestBody->name);
            $response->getBody()->write((string) new PetJsonSerialized($newPet));
        } catch (Exception $exception) {
            $response = $response->withStatus(400);
            $response->getBody()->write($exception->getMessage());
        }

        return $response;
    }

    private function put(ServerRequestInterface $request): ResponseInterface
    {
        $requestBody = json_decode((string) $request->getBody());
        $response = new Response();
        try {
            $pet = $this->petService->edit(
                $requestBody->id,
                $requestBody->name
            );
            $response->getBody()->write((string) new PetJsonSerialized($pet));
        } catch (Exception $exception) {
            $response = $response->withStatus(400);
            $response->getBody()->write($exception->getMessage());
        }

        return $response;
    }

    private function delete(ServerRequestInterface $request): ResponseInterface
    {
        $searchSegment = explode('/', $request->getUri()->getPath())[2] ?? null;
        $response = new Response();
        try {
            if (! is_numeric($searchSegment)) {
                throw new HttpException('Invalid Request', 400);
            }

            try {
                $this->petService->remove((int) $searchSegment);
            } catch (Exception $e) {
                throw new HttpException($e->getMessage(), 404);
            }

        } catch (HttpException $httpException) {
            $response = $response->withStatus($httpException->getCode() === 0 ? 500 : $httpException->getCode());
            $response->getBody()->write($httpException->getMessage());
        }

        return $response;
    }
}

ApplicationWrapper::run(PetController::class, [AuthMiddleware::class]);
