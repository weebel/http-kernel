<?php

namespace Weebel\HttpKernel;

use Symfony\Component\HttpFoundation\Response;
use Throwable;
use Weebel\Contracts\Configuration;
use Weebel\Contracts\ExceptionHandlerInterface;

class ExceptionHandler implements ExceptionHandlerInterface
{
    protected bool $debug;

    public function __construct(protected Configuration $configuration)
    {
        $this->debug = $this->configuration->isDebug();
    }


    /**
     * @throws \JsonException
     */
    public function handle(Throwable $exception): void
    {
        $response = $this->defaultExceptionResponse($exception);
        $response->send();
    }

    /**
     * @throws \JsonException
     */
    protected function defaultExceptionResponse(Throwable $exception): Response
    {
        return new Response(json_encode([
            "message" => $exception->getMessage(),
            "code" => $exception->getCode(),
            "file" => $exception->getFile(),
            "line" => $exception->getLine(),
            "trace" => $exception->getTrace()
        ], JSON_THROW_ON_ERROR), 500, ['Content-Type' => 'application/json']);
    }
}
