<?php

namespace App;

class Router
{
    private array $routes = [];
    private Request $request;
    private Response $response;

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function get($route, $callback, $middleware = []): void
    {
        $this->routes['GET'][$route] = ['callback' => $callback, 'middleware' => $middleware];
    }

    public function post($route, $callback, $middleware = []): void
    {
        $this->routes['POST'][$route] = ['callback' => $callback, 'middleware' => $middleware];
    }

    public function put($route, $callback, $middleware = []): void
    {
        $this->routes['PUT'][$route] = ['callback' => $callback, 'middleware' => $middleware];
    }

    public function delete($route, $callback, $middleware = []): void
    {
        $this->routes['DELETE'][$route] = ['callback' => $callback, 'middleware' => $middleware];
    }

    public function handleRequest(): void
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? '';
        $requestUri = $_SERVER['REQUEST_URI'] ?? '';
        $relativePath = substr($requestUri, strlen(BASE_URL));

        $uri = parse_url($relativePath, PHP_URL_PATH);
        if (isset($this->routes[$method][$uri])) {
            $route = $this->routes[$method][$uri];
            $callback = $route['callback'];
            $middleware = $route['middleware'];

            $next = function ($request, $response) use ($callback) {
                $callback($request, $response);
            };

            foreach (array_reverse($middleware) as $mw) {
                $next = function ($request, $response) use ($mw, $next) {
                    $middlewareInstance = is_callable($mw) ? $mw() : new $mw($next);
                    $middlewareInstance->handle($request, $response);
                };
            }

            $next($this->request, $this->response);
        } else {
            $this->response->withStatus(404)->error('Not Found', ['error' => 'Not Found']);
        }
    }
}
