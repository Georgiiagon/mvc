<?php

namespace Core;

class Dispatcher {

    private $params = [
		'controller' => 'task',
		'action' => 'index',
        'params' => '',
	];

    public function dispatch()
    {
        $request = new Request;
        $url = $request->url;

        $this->parseUrl($url);
        $controller = $this->toStudlyCaps($this->params['controller']);
        $controller = 'App\Controllers\\' . $controller . 'Controller';

        if (class_exists($controller)) {
            $action = $this->toCamelCase($this->params['action']);

            $controllerObject = new $controller();

            call_user_func_array([$controllerObject, $action], $this->params['params']);
        } else {
            throw new \Exception("$controller not found");
        }
    }

    private function parseUrl($url)
    {
        $urlComponents = parse_url($url ?? '/');

        parse_str($urlComponents['query'] ?? '', $this->params['params']);

        $segments = explode('/', $urlComponents['path']);

        if (isset($segments[1]) && $segments[1])
            $this->params['controller'] = $segments[1];

        if (isset($segments[2]) && $segments[2])
            $this->params['action'] = $segments[2];

        if (isset($segments[3]) && $segments[3])
            $this->params['id'] = $segments[3];
    }

    protected function toStudlyCaps($str)
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $str)));
    }

    protected function toCamelCase($str)
    {
        return lcfirst($this->toStudlyCaps($str));
    }
}
