<?php
$classLoader = require __DIR__."/../vendor/autoload.php";
$discovery = new \Atomino\Mosaic\GateWay\Discover(
	$classLoader,
	"ApiGateway",
	__DIR__.'/../var/routes.php');
$discovery->discover();
