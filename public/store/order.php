<?php

declare(strict_types=1);

use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TheNoFramework\ApplicationWrapper;
use TheNoFrameworkPetstore\Application\AuthMiddleware;
use TheNoFrameworkPetstore\Application\HttpException;
use TheNoFrameworkPetstore\Domain\StoreOrderService;
use TheNoFrameworkPetstore\Presentation\StoreOrderJsonSerialized;

final class StoreOrderController implements RequestHandlerInterface
{
    private StoreOrderService $storeOrderService;

    public function __construct(StoreOrderService $storeOrderService)
    {
        $this->storeOrderService = $storeOrderService;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        switch (strtoupper($request->getMethod())) {
            case 'GET':
                return $this->get($request);
            case 'POST':
                return $this->post($request);
            case 'DELETE':
                return $this->delete($request);
        }
        return (new Response())->withStatus(405, 'Method not allowed');
    }

    private function get(ServerRequestInterface $request): ResponseInterface
    {
        $id = explode('/', $request->getUri()->getPath())[3] ?? null;
        $response = new Response();
        try {
            if (! is_numeric($id)) {
                throw new HttpException('Invalid Request', 400);
            }
            $order = $this->storeOrderService->find((int) $id);
            if ($order === null) {
                throw new HttpException('Order not found', 404);
            }
            $response->getBody()->write((string) new StoreOrderJsonSerialized($order));
        } catch (HttpException $exception) {
            $response = $response->withStatus($exception->getCode() === 0 ? 500 : $exception->getCode());
            $response->getBody()->write($exception->getMessage());
        }

        return $response;
    }

    private function post(ServerRequestInterface $request): ResponseInterface
    {
        $requestBody = json_decode((string) $request->getBody());
        $response = new Response();
        try {
            $newOrder = $this->storeOrderService->create(
                $requestBody->petId,
                new \DateTimeImmutable($requestBody->shipDate)
            );
            $response->getBody()->write((string) new StoreOrderJsonSerialized($newOrder));
        } catch (\Exception $exception) {
            $response = $response->withStatus('400');
            $response->getBody()->write($exception->getMessage());
        }
        return $response;
    }

    private function delete(ServerRequestInterface $request): ResponseInterface
    {
        $id = explode('/', $request->getUri()->getPath())[3] ?? null;
        $response = new Response();
        try {
            if (! is_numeric($id)) {
                throw new HttpException('Invalid Request', 400);
            }
            try {
                $this->storeOrderService->remove((int) $id);
            } catch (\Exception $e) {
                throw new HttpException($e->getMessage(), 404);
            }

        } catch (HttpException $exception) {
            $response = $response->withStatus($exception->getCode() === 0 ? 500 : $exception->getCode());
            $response->getBody()->write($exception->getMessage());
        }
        return $response;
    }
}

ApplicationWrapper::run(StoreOrderController::class, [AuthMiddleware::class]);
