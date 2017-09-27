<?php

/**
 * @package  Simplex v1.0.0
 * @author   wu jun <cddxwujun@vip.qq.com>
 */

require_once __DIR__.'/../vendor/autoload.php';

$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

/**
 * @description  Introduce the framework handler
 */

require_once __DIR__.'/../app/app.php';