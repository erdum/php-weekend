<?php

namespace PhpWeekend;

class App {

    protected static function send_status_code($code)
    {
        is_callable('http_send_status') ? http_send_status($code)
            : header("HTTP/1.1 $code");
    }

    public static function get_request()
    {
        $request = !empty($_POST) ? $_POST : array();
        $request = empty($request) && !empty($_GET) ? $_GET : $request;

        return isset($_SERVER['CONTENT_TYPE'])
            && $_SERVER['CONTENT_TYPE'] == 'application/json'
            ? json_decode(file_get_contents('php://input'), true)
            : $request;
    }

    public static function send_json($payload, $status_code = 200)
    {
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        self::send_status_code($status_code);

        return exit(json_encode((object) $payload));
    }

    public static function send_template($template, $data = array())
    {
        $filename = strpos($template, '.php') == false ? $template . '.php'
            : $template;
        extract($data);
        
        include(__DIR__ . '/../templates/' . $filename);
    }

    public static function send_response(
        $response,
        $status_code = 200,
        $content_type = 'text/html'
    )
    {
        header('Content-Type: ' . $content_type);
        self::send_status_code($status_code);
        return exit($response);
    }

    public static function send_file($filename)
    {
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filename));
        readfile($filename);
    }

    public static function set_csrf()
    {
        session_start();

        if (!isset($_SESSION["csrf"]))
            $_SESSION["csrf"] = bin2hex(random_bytes(50));
        
        return '<input type="hidden" name="csrf" value="'
            . $_SESSION["csrf"] . '">';
    }

    public static function is_csrf_valid()
    {
        session_start();
        $request = get_request();

        return isset($_SESSION['csrf']) && isset($request['csrf'])
            && $_SESSION['csrf'] == $request['csrf'];
    }
}
