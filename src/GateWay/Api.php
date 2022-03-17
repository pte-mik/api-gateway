<?php namespace ApiGateway\GateWay;

use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Cache\ItemInterface;

class Api {

	protected string $method = "GET";
	protected string|null $body = null;
	protected array $headers = [];
	protected array $query = [];

	public static function route($routes, FilesystemAdapter $cache) {

		$request = Request::createFromGlobals();
		$path = trim($request->getPathInfo(), '/');
		$method = $request->getMethod();
		$key = md5($method . '::' . $path);
		if($request->headers->has("no-cache")) $cache->deleteItem($key);

		$response = $cache->get($key, function (ItemInterface $item) use ($method, $path, $routes, $request) {
			if (!array_key_exists($method, $routes)) return false;
			foreach ($routes[$method] as $pathPattern => $handler) {
				if (preg_match($handler["pattern"], $path, $matches)) {
					array_shift($matches);
					$item->expiresAfter($handler["cache"]);
					$args = [];
					foreach ($matches as $arg) {
						$splitted = explode("/", $arg);
						$args += $splitted;
						$class = $handler["class"];
						$method = $handler["method"];
						/** @var Response $response */
					}
					return (new $class($request))->$method(...$args);
				}
			}
			return new Response("404", 404);
		});

		$response->send();

	}

	public function __construct(protected Request|null $request) {
		if ($this->request !== null) {
			$this->method = $this->request->getMethod();
			$keys = $this->request->headers->keys();
			foreach ($keys as $key) $this->headers[$key] = $this->request->headers->get($key);
			$this->body = $this->request->getContent();
			$this->query = $this->request->query->all();
		}
	}

	protected function createClient(string $url, string|null $method = null, bool $clone = true): Client {
		if ($method === null) $method = $this->method;
		$client = new Client($url, $method);
		if ($clone) {
			$client->setBody($this->body);
			$client->setQuery($this->query);
		}
		return $client;
	}

	protected function fastForward(string $url, string|null $method = null): Response {
		$result = $this->forward($url, $method);
		return $this->createResponse($result);
	}

	protected function forward(string $url, string|null $method = null, bool $autoSend = false): Result {
		$client = $this->createClient($url, $method, true);
		return $client->send();
	}

	protected function createResponse(Result $originalResponse): Response {
		$response = new Response();
		$response->headers->set("content-type", $originalResponse->getContentType());
		$response->setStatusCode($originalResponse->getStatusCode());
		$response->setContent($originalResponse->getContent());
		return $response;
	}
}