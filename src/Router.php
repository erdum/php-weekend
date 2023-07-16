<?php

namespace PhpWeekend;

class Router {

  public static function get($route, $callback)
  {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') self::route($route, $callback);
  }

  public static function post($route, $callback)
  {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') self::route($route, $callback);
  }

  public static function put($route, $callback)
  {
    if ($_SERVER['REQUEST_METHOD'] == 'PUT') self::route($route, $callback);
  }

  public static function patch($route, $callback)
  {
    if ($_SERVER['REQUEST_METHOD'] == 'PATCH') self::route($route, $callback);
  }

  public static function delete($route, $callback)
  {
    if ($_SERVER['REQUEST_METHOD'] == 'DELETE') self::route($route, $callback);
  }

  public static function any($route, $callback)
  { 
    self::route($route, $callback);
  }

  private static function route($route, $callback)
  {
    $request_url = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);
    $request_url = rtrim($request_url, '/');
    $request_url = strtok($request_url, '?');
    $route_parts = explode('/', $route);
    $request_url_parts = explode('/', $request_url);
    array_shift($route_parts);
    array_shift($request_url_parts);

    if ($route_parts[0] == '' && count($request_url_parts) == 0) {
      
      // Callback function
      if (is_callable($callback)) {
        call_user_func_array($callback, array());
        exit();
      }
    }

    if (count($route_parts) != count($request_url_parts)) return;
    $parameters = array();

    for($__i__ = 0; $__i__ < count($route_parts); $__i__++) {
      $route_part = $route_parts[$__i__];

      if (preg_match("/^[$]/", $route_part)) {
        $route_part = ltrim($route_part, '$');
        array_push($parameters, $request_url_parts[$__i__]);
      } else if ($route_parts[$__i__] != $request_url_parts[$__i__]) {
        return;
      }
    }

    // Callback function
    if (is_callable($callback)) {
      call_user_func_array($callback, $parameters);
      exit();
    }
  }
}
