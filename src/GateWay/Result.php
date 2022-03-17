<?php

namespace ApiGateway\GateWay;



use Symfony\Contracts\HttpClient\ResponseInterface;

class Result {
	private array $headers;
	private string $contentType = "text";
	public function __construct(protected ResponseInterface $response) {
		$this->headers = $this->response->getHeaders(false);
		$this->contentType = $this->getHeader("content-type");
	}
	public function getHeaders(): array { return $this->headers; }
	public function getHeader($key): string { return array_key_exists($key, $this->headers) ? $this->headers[$key][0] : ""; }
	public function getInfo(): array { return $this->response->getInfo(); }
	public function getContent(): string { return $this->response->getContent(false); }
	public function getStatusCode(): int { return $this->response->getStatusCode(false); }
	public function getJson(): mixed { return json_decode($this->response->getContent(false), true); }
	public function getContentType(): string { return $this->contentType; }
}