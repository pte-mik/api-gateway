<?php

use Atomino\Mosaic\GateWay\GateWay;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpFoundation\Request;

require __DIR__ . "/../vendor/autoload.php";

try {
	(new GateWay(
		Request::createFromGlobals(),
		require __DIR__ . "/../var/routes.php",
		new FilesystemAdapter('', -1, __DIR__ . "/../var/cache")
	))->route();
}catch (\Throwable $e){
	error_log(date("Y-m-d H:i:s").' '.$e->getMessage()." (".get_class($e).")\n", 3, __DIR__ . "/../var/error.log");
}