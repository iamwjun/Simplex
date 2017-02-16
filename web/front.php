<?php
require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$request = Request::createFromGlobals();
$response = new Response();

$map = array(
    '/bye' => __DIR__.'/../src/pages/bye.php',
    '/hello' => __DIR__.'/../src/pages/hello.php',
);

$path = $request->getPathInfo();

if (isset($map[$path])){
    ob_start();
    include $map[$path];
    $response->setContent(ob_get_clean());
}else{
    $response->setStatusCode(400);
    $response->setContent('Not found!');
}

$response->send();