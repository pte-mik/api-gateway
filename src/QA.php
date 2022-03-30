<?php

namespace ApiGateway;

use Atomino\Mosaic\Client\ApiRequestResult;
use Atomino\Mosaic\Client\EventRequest;
use Atomino\Mosaic\GateWay\App;
use Atomino\Mosaic\GateWay\Attributes\{ApiBase, EventHandler, Forward, Handle, Subscribe};
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;

#[ApiBase("https://qa.app.mik.pte.hu")]
#[EventHandler("https://qa.app.mik.pte.hu")]
#[Subscribe("classroom.*")]
#[Subscribe("user.*")]
#[Forward("classroom/v1", "classroom/location", 10)]
class QA extends App {

	#[Handle("gituser", 10)]
	public function fetchGitUserData($data): Response {
		$response = new ApiRequestResult(HttpClient::create()->request("GET", "https://api.github.com/users/laborci", []));
		return new Response($response->getBody(), $response->getStatusCode(), ["content-type" => $response->getHeader("content-type")]);
	}

	#[Subscribe("test")]
	public function handleCustomEvent(string $event, mixed $data) {
		EventRequest::create("https://qa.app.mik.pte.hu")->send($event, $data);
	}

	#[Subscribe("valami.*")]
	public function a(string $event, mixed $data) {
		EventRequest::create("http://127.0.0.31:8000")->send("test", $data);
	}

}

