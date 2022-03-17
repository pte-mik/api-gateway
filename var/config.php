<?php return array (
  'HEAD' => 
  array (
  ),
  'GET' => 
  array (
    'classroom/v2/*' => 
    array (
      'pattern' => '#^classroom/v2/([^\\/]+)$#',
      'class' => 'ApiGateway\\Api\\Test',
      'method' => 'test2',
      'cache' => -1,
    ),
    'classroom/*' => 
    array (
      'pattern' => '#^classroom/([^\\/]+)$#',
      'class' => 'ApiGateway\\Api\\Test',
      'method' => 'test',
      'cache' => 10,
    ),
    'classroom/**' => 
    array (
      'pattern' => '#^classroom/(.*?)$#',
      'class' => 'ApiGateway\\Api\\Test',
      'method' => 'test',
      'cache' => 10,
    ),
  ),
  'POST' => 
  array (
  ),
  'PUT' => 
  array (
  ),
  'PATCH' => 
  array (
  ),
  'DELETE' => 
  array (
  ),
  'PURGE' => 
  array (
  ),
  'OPTIONS' => 
  array (
  ),
  'TRACE' => 
  array (
  ),
  'CONNECT' => 
  array (
  ),
);