<?php

session_start();

include_once(__DIR__ . '/router.php');

function get_request()
{
    $request = !empty($_POST) ? $_POST : array();
    $request = empty($_POST) && !empty($_GET) ? $_GET : array();

    return isset($_SERVER['CONTENT_TYPE']) && $_SERVER['CONTENT_TYPE'] == 'application/json'
        ? json_decode(file_get_contents('php://input'), true)
        : $request;
}

function send_json($payload, $status_code = 200)
{
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    is_callable('http_send_status') ? http_send_status($status_code) : header("HTTP/1.1 $status_code");

    return exit(json_encode((object) $payload));
}

function send_template($template, $data = [])
{
    $filename = strpos($template, '.php') == false ? $template . '.php' : $template;
    extract($data);
    
    include(__DIR__ . '/../templates/' . $filename);
}

function set_csrf()
{
    if (!isset($_SESSION["csrf"])) $_SESSION["csrf"] = bin2hex(random_bytes(50));
    
    return '<input type="hidden" name="csrf" value="' . $_SESSION["csrf"].'">';
}

function is_csrf_valid()
{
    $request = get_request();

    return isset($_SESSION['csrf']) && isset($request['csrf'])
        && $_SESSION['csrf'] == $request['csrf'];
}
