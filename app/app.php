<?php
use Symfony\Component\HttpFoundation\Request;

$request = Request::createFromGlobals();

$app = new \App\Simplex\Simplex($request);

$app->handle();