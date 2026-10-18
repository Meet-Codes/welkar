<?php

namespace App\Support;

class Router
{
    private array $routes = [];
    private array $namedRoutes = [];

    public function add(string $method, string $path, mixed $handler, array $middleware = []): self
    {
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', trim($path, '/'));
        $regex = '#^/' . ($pattern === '' ? '' : $pattern) . '$#';

        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => '/' . trim($path, '/'),
            'regex' => $regex,
            'handler' => $handler,
            'middleware' => $middleware,
        ];

        return $this;
    }

    public function get(string $path, mixed $handler, array $middleware = []): self
    {
        return $this->add('GET', $path, $handler, $middleware);
    }

    public function post(string $path, mixed $handler, array $middleware = []): self
    {
        return $this->add('POST', $path, $handler, $middleware);
    }

    public function put(string $path, mixed $handler, array $middleware = []): self
    {
        return $this->add('PUT', $path, $handler, $middleware);
    }

    public function delete(string $path, mixed $handler, array $middleware = []): self
    {
        return $this->add('DELETE', $path, $handler, $middleware);
    }

    public function dispatch(Request $request): void
    {
        $method = $request->method();
        $matchMethod = $method === 'HEAD' ? 'GET' : $method;
        $uri = $request->uri();

        foreach ($this->routes as $route) {
            if ($route['method'] !== $matchMethod) {
                continue;
            }

            if (preg_match($route['regex'], $uri, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                // Execute middleware pipeline
                $pipeline = $route['middleware'];
                $runner = function () use ($route, $request, $params) {
                    $handler = $route['handler'];
                    if (is_callable($handler)) {
                        return call_user_func($handler, $request, ...$params);
                    }

                    if (is_array($handler)) {
                        [$class, $action] = $handler;
                        $controller = new $class();
                        return call_user_func_array([$controller, $action], array_merge([$request], $params));
                    }

                    throw new \Exception("Invalid route handler.");
                };

                $this->runPipeline($pipeline, $request, $runner);
                return;
            }
        }

        // 404 Not Found
        http_response_code(404);
        echo View::render('errors.404', ['title' => 'Page Not Found'], 'layouts.storefront');
    }

    private function runPipeline(array $middlewareList, Request $request, callable $target): void
    {
        $next = $target;

        while ($middlewareClass = array_pop($middlewareList)) {
            $prevNext = $next;
            $next = function () use ($middlewareClass, $request, $prevNext) {
                $instance = new $middlewareClass();
                return $instance->handle($request, $prevNext);
            };
        }

        $result = $next();
        if (is_string($result)) {
            echo $result;
        }
    }
}
