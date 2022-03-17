<?php namespace ApiGateway\GateWay;

use Atomino\Neutrons\Attr;

#[\Attribute(\Attribute::IS_REPEATABLE | \Attribute::TARGET_METHOD)]
class Cache extends Attr {
	public function __construct(public int $timeout) {	}
}