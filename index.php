<?php

require_once(__DIR__ . '/core/core.php');

get('/', function () {
    send_template('home', array('data' => 'Hello, World!'));
});
