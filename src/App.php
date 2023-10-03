<?php

namespace PhpWeekend;

class App {

    public static function get_request()
    {
        $request = !empty($_POST) ? $_POST : array();
        $request = empty($_POST) && !empty($_GET) ? $_GET : $request;

        return isset($_SERVER['CONTENT_TYPE']) && $_SERVER['CONTENT_TYPE'] == 'application/json'
            ? json_decode(file_get_contents('php://input'), true)
            : $request;
    }

    public static function send_json($payload, $status_code = 200)
    {
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        is_callable('http_send_status') ? http_send_status($status_code) : header("HTTP/1.1 $status_code");

        return exit(json_encode((object) $payload));
    }

    public static function send_template($template, $data = [])
    {
        $filename = strpos($template, '.php') == false ? $template . '.php' : $template;
        extract($data);
        
        include(__DIR__ . '/../templates/' . $filename);
    }

    public static function set_csrf()
    {
        session_start();

        if (!isset($_SESSION["csrf"])) $_SESSION["csrf"] = bin2hex(random_bytes(50));
        
        return '<input type="hidden" name="csrf" value="' . $_SESSION["csrf"].'">';
    }

    public static function is_csrf_valid()
    {
        session_start();
        $request = get_request();

        return isset($_SESSION['csrf']) && isset($request['csrf'])
            && $_SESSION['csrf'] == $request['csrf'];
    }
}
