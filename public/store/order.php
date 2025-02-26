<?php

declare(strict_types=1);

use TheNoFrameworkPetstore\Domain\StoreOrder;
use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TheNoFramework\ApplicationWrapper;
use TheNoFrameworkPetstore\Application\AuthMiddleware;
use TheNoFrameworkPetstore\Application\HttpException;
use TheNoFrameworkPetstore\Domain\StoreOrderService;
use TheNoFrameworkPetstore\Presentation\StoreOrderJsonSerialized;

final readonly class StoreOrderController implements RequestHandlerInterface
{
    public function __construct(private StoreOrderService $storeOrderService)
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return match (strtoupper($request->getMethod())) {
            'GET' => $this->get($request),
            'POST' => $this->post($request),
            'DELETE' => $this->delete($request),
            default => (new Response())->withStatus(405, 'Method not allowed'),
        };
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
            if (!$order instanceof StoreOrder) {
                throw new HttpException('Order not found', 404);
            }

            $response->getBody()->write((string) new StoreOrderJsonSerialized($order));
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
            $newOrder = $this->storeOrderService->create(
                $requestBody->petId,
                new DateTimeImmutable($requestBody->shipDate)
            );
            $response->getBody()->write((string) new StoreOrderJsonSerialized($newOrder));
        } catch (Exception $exception) {
            $response = $response->withStatus(400);
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

ApplicationWrapper::run(StoreOrderController::class, [AuthMiddleware::class]);
