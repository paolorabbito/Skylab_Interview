<?php

    $routes = [];

    function route($action, $callback){
        global $routes;

        $action = trim($action, '/');

        $routes[$action] = $callback;
    }

    function dispatch($action){
        global $routes;

        $action = trim($action, '/');

        $callback = null;

        $params = [];

        foreach($routes as $route => $handler){
            if(preg_match("%^{$route}$%", $action, $matches)===1){

                $callback = $handler;

                unset($matches[0]);
                $params = $matches;

                break;
            }
        }

        echo call_user_func($callback, ...$params);
    }