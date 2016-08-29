<?php
define('APP_PATH', realpath('.'));
define('DS', "::");

include APP_PATH . '/vendor/autoload.php';

spl_autoload_register(function($class) {
    static $ds = '/';

    $_route_map = ['baohan\SwooleJob' => APP_PATH . '/src/'];

    $parts  = explode('\\', $class);
    $app    = array_shift($parts);
    $module = array_shift($parts);
    if(!isset($_route_map[$app."\\".$module])) {
        echo 'Can not found file '.$class;
        exit();
    }
    $path = $_route_map[$app."\\".$module] . str_replace('\\', $ds, implode('\\', $parts)) . '.php';
    include $path;

});


try {
    $router = new \baohan\SwooleJob\Router();
    $router->setPrefix("\\App\\Job\\");
    $router->setExecutor("execute");
    $router->setDecode(function($data) {
        return json_decode($data, true);
    });

    $serv = new \baohan\SwooleJob\Server($router);
    $serv->setSwoolePort(9505);
    // custom callback event
    $serv->setEvtStart(function($serv) {
        echo "server start!" . PHP_EOL;
    });
    $serv->start();
} catch(\Exception $e) {
    echo "Caught Exception: " . $e->getMessage() . PHP_EOL;
}
