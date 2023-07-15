<?php

function get($route, $callback)
{
  if ($_SERVER['REQUEST_METHOD'] == 'GET') route($route, $callback);
}

function post($route, $callback)
{
  if ($_SERVER['REQUEST_METHOD'] == 'POST') route($route, $callback);
}

function put($route, $callback)
{
  if ($_SERVER['REQUEST_METHOD'] == 'PUT') route($route, $callback);
}

function patch($route, $callback)
{
  if ($_SERVER['REQUEST_METHOD'] == 'PATCH') route($route, $callback);
}

function delete($route, $callback)
{
  if ($_SERVER['REQUEST_METHOD'] == 'DELETE') route($route, $callback);
}

function any($route, $callback)
{ 
    route($route, $callback);
}

function route($route, $callback)
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
