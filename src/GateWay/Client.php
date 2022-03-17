<?php namespace ApiGateway\GateWay;

use Symfony\Component\HttpClient\HttpClient;

class Client {
	private array $headers = [];
	private string $body = "";
	private array $query = [];

	public function __construct(private string $url, private string $method = "GET") { }

	public function setContentType(string $cotentType = "text/plain") { $this->setHeader('content-type', $cotentType); }
	public function setAccept(string $cotentType = "application/json") { $this->setHeader('accept', $cotentType); }

	public function setHeaders(array $headers) { $this->headers = $headers; }
	public function setHeader(string $key, string|null $value = null) {
		if ($value === null && array_key_exists($key, $this->headers)) unset($this->headers[$key]);
		else $this->headers[$key] = $value;
	}
	public function setBody(string|null $body) { $this->body = $body??""; }
	public function setQuery(array $query) { $this->query = $query; }
	public function setQueryValue(string $key, string|null $value = null) {
		if ($value === null && array_key_exists($key, $this->query)) unset($this->query[$key]);
		else $this->query[$key] = $value;
	}
	public function setJson(mixed $data) {
		$this->setContentType("application/json");
		$this->body = json_encode($data);
	}

	public function send() {
		return new Result(HttpClient::create()->request($this->method, $this->url, [
			"headers" => $this->headers,
			"body"    => $this->body,
			"query"   => $this->query,
		]));
	}
}