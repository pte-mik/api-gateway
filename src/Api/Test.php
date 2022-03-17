<?php namespace ApiGateway\Api;

use ApiGateway\GateWay\Api;
use ApiGateway\GateWay\Cache;
use ApiGateway\GateWay\Route;
use Symfony\Component\HttpFoundation\Response;

class Test extends Api {

	#[Route("/classroom/*", Route::GET)]
	#[Route("/classroom/**", Route::GET)]
	#[Cache(10)]
	public function test($name): Response {
		return $this->fastForward("https://api.github.com/users/{$name}");
	}

	#[Route("/classroom/v2/*", Route::GET)]
	public function test2($name): Response {
		$client = $this->createClient("https://api.github.com/users/{$name}", "GET");
		$client->setAccept("application/json");
		$result = $client->send();
		$data = $result->getJson();
		$data["hellooo"] = time();
		$response = $this->createResponse($result);
		$response->setContent(json_encode($data));
		return $response;
	}
}