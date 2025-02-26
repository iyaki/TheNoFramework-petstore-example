<?php

declare(strict_types=1);

namespace TheNoFrameworkPetstore\Application;

use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class AuthMiddleware implements MiddlewareInterface
{
    private const string TOKEN = 'Bearer iamatoken';

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $authToken = strtolower($request->getHeaderLine('Authorization'));
        if (strtolower(self::TOKEN) !== $authToken) {
            $response = new Response();
            $response->getBody()->write('Invalid Auth Token');
            return $response->withStatus(401);
        }

        return $handler->handle($request);
    }
}
