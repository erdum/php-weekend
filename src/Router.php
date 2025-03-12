<?php

namespace PhpWeekend;

class Router {

    private static $prefix = '';

    public static function set_router_prefix($prefix)
    {
        self::$prefix = rtrim($prefix, '/ ');
    }

    public static function get($route, $callback)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET'){
            self::route($route, $callback);
        }
    }

    public static function post($route, $callback)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            self::route($route, $callback);
        }
    }

    public static function put($route, $callback)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
            self::route($route, $callback);
        }
    }

    public static function patch($route, $callback)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'PATCH') {
            self::route($route, $callback);
        }
    }

    public static function delete($route, $callback)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
            self::route($route, $callback);
        }
    }

    public static function any($route, $callback)
    { 
        self::route($route, $callback);
    }

    private static function route($route, $callback)
    {
        $request_url = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);
        $request_url = rtrim($request_url, '/ ');

        // If request URL doesn't contain router prefix
        if (
            ! preg_match('/^'.preg_quote(self::$prefix, '/').'/', $request_url)
        ) return;

        $request_url = str_replace(self::$prefix, '', $request_url);
        $request_url_parts = explode('/', $request_url);
        array_shift($request_url_parts);

        $route_url = rtrim($route, '/ ');
        $route_url_parts = explode('/', $route_url);
        array_shift($route_url_parts);

        $route_params = array();

        foreach ($route_url_parts as $index => $route_part) {

            // Route part is a parameter
            if (preg_match('/^\$/', $route_part)) {
                $route_param = $request_url_parts[$index];

                // Router parameter is optional
                if (preg_match('/\?$/', $route_part)) {

                    if (empty($route_param)) unset($route_url_parts[$index]);
                    array_push($route_params, $route_param ?: null);
                } else {
                    
                    if (empty($route_param)) return;
                    array_push($route_params, $route_param);
                }
            } else {

                // Request URL part doesn't match with route part
                if ($route_part !== $request_url_parts[$index]) return;
            }
        }

        if (count($request_url_parts) !== count($route_url_parts)) return;

        if (is_callable($callback)) {
            call_user_func_array($callback, $route_params);
            exit();
        }
    }
}
