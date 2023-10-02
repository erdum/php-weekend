
# PHP-Weekend

A simple minimal set of functions provide you the ability to build API & web application with no setup time
<img src="https://i.imgur.com/pLikazr.png" width="800" height="500" />

## Table of Contents

- [Features](#features)
- [Installation](#installation)
- [Usage](#usage)
- [Feedback](#feedback)
- [Contributors](#contributors)
- [License](#license)

## Features

- Routing with dynamic routes
- Templating
- Method for accessing request data
- Method for sending json data
- Customizable
- No design restriction build your API in anyway you want
- Zero configuration


## Installation

Install PHP-Weekend

```bash
composer require erdum/php-weekend
```
    
## Usage

After installing the package your project directory will have the following

```bash
ls
```
- composer.json
- composer.lock
- vendor

Create an index.php file

```php
<?php

require(__DIR__ . '/vendor/autoload.php');

use PhpWeekend\Router;
use PhpWeekend\App;

Router::get('/', function() {
    App::send_json(['data' => 'Hello, World!']);
});

/*
Router also have
post
put
patch
delete
any
*/
```

You can get request data submitted in any format with a single function
- query Parameters
- form-data
- multipart/form-data
- application/json

```php
<?php

require(__DIR__ . '/vendor/autoload.php');

use PhpWeekend\Router;
use PhpWeekend\App;

Router::post('/', function() {
    $request_payload = App::get_request();

    App::send_json(['data' => $request_payload], 201);
});
```

You can also build dynamic routes
```php
<?php

require(__DIR__ . '/vendor/autoload.php');

use PhpWeekend\Router;
use PhpWeekend\App;

Router::get('/user/$name', function($name) {
    App::send_json(['data' => $name]);
});
```

You can also use templates create a directory called templates in your project root

```bash
mkdir templates
```
inside your templates directory you can build your templates like home.php

```php
<h1><?= $data ?></h1>
<h2><?= $foo ?></h2>

<?php if ($age > 18): ?>
    Hello
<?php else: ?>
    whatsapp
<?php endif; ?>
```

now you can render home.php template from your index file

```php
<?php

require(__DIR__ . '/vendor/autoload.php');

use PhpWeekend\Router;
use PhpWeekend\App;

Router::get('/', function() {
    App::send_template('home', [
        'data' => 'Hello, World!',
        'foo' => 'bar',
        'age' => 18
    ]);
});
```

App also provides you the csrf functionality

```php
<form>
    <input type="name" name="user-name">
    <input type="email" name="user-email">
    <?= set_csrf() ?>
</form>
```

now verify the csrf token in the handler
```php
<?php

require(__DIR__ . '/vendor/autoload.php');

use PhpWeekend\Router;
use PhpWeekend\App;

Router::get('/', function() {

    if (App::is_csrf_valid()) {
        // CSRF token validated
    }
});
```
## Feedback

If you have any feedback, please reach out to us at erdumadnan@gmail.com

## Contributors

<a href="https://github.com/erdum/php-weekend/graphs/contributors">
  <img src="https://contrib.rocks/image?repo=erdum/php-weekend" />
</a>

## License
[![MIT License](https://img.shields.io/badge/License-MIT-green.svg)](https://choosealicense.com/licenses/mit/)

