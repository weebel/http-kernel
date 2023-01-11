<?php

namespace Weebel\HttpKernel;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Weebel\Contracts\Container;
use Weebel\Contracts\EventDispatcher;

class HttpRequestHandler
{
    public function __construct(protected Container $container, protected EventDispatcher $eventDispatcher)
    {
    }

    public function handle(Request $request): Response
    {
        $this->registerRequestInContainer($request);

        $response = new Response();

        $this->registerResponseInContainer($response);

        $requestResponse = new RequestResponse($request, $response);

        $this->registerRequestResponseInContainer($requestResponse);
        $this->eventDispatcher->dispatchByTag('http.request', $requestResponse);

        return $requestResponse->response;
    }

    private function registerRequestInContainer(Request $request): void
    {
        $this->container->set(Request::class, $request);
        $this->container->set('request', $request);
    }

    private function registerResponseInContainer(Response $response): void
    {
        $this->container->set(Response::class, $response);
        $this->container->set('response', $response);
    }

    /**
     * @param RequestResponse $requestResponse
     * @return void
     */
    private function registerRequestResponseInContainer(RequestResponse $requestResponse): void
    {
        $this->container->set(RequestResponse::class, $requestResponse);
        $this->container->set('http.request', $requestResponse);
        $this->container->set('request.response', $requestResponse);
    }
}
