<?php
require __DIR__ . "/../vendor/autoload.php";
\ApiGateway\GateWay\Api::route(
	require __DIR__."/../var/config.php",
	new \Symfony\Component\Cache\Adapter\FilesystemAdapter('', -1, __DIR__ . "/../cache")
);
