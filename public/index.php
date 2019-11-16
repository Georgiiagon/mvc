<?php
spl_autoload_register(function ($className) {
	include dirname(__DIR__) . '/' . str_replace('\\', '/', $className) . '.php';
});


$route = new Core\Dispatcher();
$route->dispatch();
