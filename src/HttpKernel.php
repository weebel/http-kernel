<?php

namespace Weebel\HttpKernel;

use Symfony\Component\HttpFoundation\Request;
use Weebel\Contracts\Bootable;

class HttpKernel implements Bootable
{
    protected ?Request $request = null;

    public function __construct(protected HttpRequestHandler $handler)
    {
    }

    public function boot(): void
    {
        $request = $this->resolveRequest();
        $response = $this->handler->handle($request);
        $response->send();
    }

    protected function resolveRequest(): Request
    {
        return $this->request ?? Request::createFromGlobals();
    }

    /**
     * This method can be used for test or simulation purposes
     */
    public function setRequest(?Request $request): HttpKernel
    {
        $this->request = $request;

        return $this;
    }
}
