<?php

namespace ApiGateway\GateWay;


#[\Attribute(\Attribute::IS_REPEATABLE | \Attribute::TARGET_METHOD)]
class Route extends \Atomino\Neutrons\Attr {
	public const HEAD = 'HEAD';
	public const GET = 'GET';
	public const POST = 'POST';
	public const PUT = 'PUT';
	public const PATCH = 'PATCH';
	public const DELETE = 'DELETE';
	public const PURGE = 'PURGE';
	public const OPTIONS = 'OPTIONS';
	public const TRACE = 'TRACE';
	public const CONNECT = 'CONNECT';

	public string $class;
	public string $method;
	public array $methods;
	public string $pattern;
	public int|null $cache = null;

	public function __construct(public string $path, string ...$methods) {
		$this->path = trim($this->path, "/");
		$this->methods = array_unique($methods);
		$pattern = str_replace("**", "%", $this->path);
		$pattern = str_replace("*", "([^\\/]+)", $pattern);
		$pattern = str_replace("%", "(.*?)", $pattern);
		$pattern = "#^" . $pattern . "$#";
		$this->pattern = $pattern;
	}
}