<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Provider\ControllerServiceProvider;
use Igni\Application\HttpApplication;
use Igni\Network\Server\Configuration;
use Igni\Network\Server\HttpServer;
use Illuminate\Container\Container;
use Microparts\Igni\Support\Middleware\ErrorHandlerMiddleware;
use Microparts\Igni\Support\Modules\ConfigurationModule;
use Microparts\Igni\Support\Modules\HealthcheckModule;
use Microparts\Igni\Support\Modules\LoggerModule;
use Microparts\Igni\Support\Modules\PostgresPdoModule;
use Microparts\Igni\Support\Modules\ServiceInfoModule;

// Setup server
$conf = new Configuration(8080, '0.0.0.0');
$conf->setWorkers(4);

$server = new HttpServer($conf);
$container = new Container();

// Setup application and routes
$app = new HttpApplication($container);
$app->use(ErrorHandlerMiddleware::class);
$app->use(new Tuupola\Middleware\CorsMiddleware([
    'origin'         => ['*'],
    'methods'        => ['HEAD', 'GET', 'OPTIONS', 'POST', 'PUT', 'PATCH', 'DELETE'],
    'headers.allow'  => ['Authorization', 'Content-Type', 'Accept-Encoding'],
    'headers.expose' => ['*'],
    'credentials'    => false,
    'cache'          => 0,
]));
$app->extend(HealthcheckModule::class);
$app->extend(LoggerModule::class);
$app->extend(ConfigurationModule::class);
$app->extend(ServiceInfoModule::class);
$app->extend(PostgresPdoModule::class);
$app->extend(ControllerServiceProvider::class);

// Run the server, it should listen on localhost:8080
$app->run($server);
