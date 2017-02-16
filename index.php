<?php
require_once __DIR__.'/init.php';

$input = $request->get('name', 'world');

$response->setContent(sprintf('Hello %s', htmlspecialchars($input, ENT_QUOTES, 'utf-8')));

$response->send();