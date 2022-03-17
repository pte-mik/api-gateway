<?php
$classLoader = require __DIR__."/../vendor/autoload.php";
$discovery = new \ApiGateway\GateWay\Discover($classLoader, __DIR__.'/../var/config.php');
$discovery->discover();
