<?php

namespace ApiGateway\GateWay;

use Atomino\Neutrons\CodeFinder;
use Composer\Autoload\ClassLoader;

class Discover {
	public function __construct(private ClassLoader $classLoader, private string $output) {

	}

	public function discover() {
		$routes = [];
		$codefinder = new CodeFinder($this->classLoader);
		$classes = $codefinder->Psr4ClassSeeker("ApiGateway\\Api");
		foreach ($classes as $class) {
			if (is_subclass_of($class, Api::class)) {
				$classReflection = new \ReflectionClass($class);
				$methods = $classReflection->getMethods();
				foreach ($methods as $method) {
					$cache = Cache::get($method);
					$routeAttributes = Route::all($method);
					foreach ($routeAttributes as $route) {
						$route->cache = $cache !== null ? $cache->timeout : -1;
						$route->class = $class;
						$route->method = $method->name;
						$route->path = trim($route->path, "/");
						$routes[] = $route;
					}
				}
			}
		}

		$flatRoutes = $this->sortRoutes($routes);
		$routes = [
			Route::HEAD    => [],
			Route::GET     => [],
			Route::POST    => [],
			Route::PUT     => [],
			Route::PATCH   => [],
			Route::DELETE  => [],
			Route::PURGE   => [],
			Route::OPTIONS => [],
			Route::TRACE   => [],
			Route::CONNECT => [],
		];

		foreach ($flatRoutes as $route) {
			foreach ($route->methods as $method) {
				$routes[$method][$route->path] = [
					"pattern" => $route->pattern,
					"class"   => $route->class,
					"method"  => $route->method,
					"cache"   => $route->cache,
				];
			}
		}
		file_put_contents($this->output, "<?php return " . var_export($routes, true) . ";");
	}


	/**
	 * @param Route[] $routes
	 * @return Route[]
	 */
	public function sortRoutes(array $routes): array {
		foreach ($routes as $route) $route->path = str_replace('**', '%', $route->path);
		usort($routes, function (Route $a, Route $b) {
			return strcmp($a->path, $b->path);
		});
		$routes = array_reverse($routes);
		foreach ($routes as $route) $route->path = str_replace('%', '**', $route->path);
		return $routes;
	}
}