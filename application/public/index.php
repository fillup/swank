<?php

error_reporting(E_ALL);
ini_set('display_errors', true);

$path = implode(DIRECTORY_SEPARATOR, array(__DIR__, '..', 'protected', 'library', ''));
set_include_path(get_include_path() . PATH_SEPARATOR . $path);

// change the following paths if necessary
$yii = __DIR__.'/../vendor/yiisoft/yii/framework/yii.php';

// Composer autoloading
if (file_exists(__DIR__.'/../vendor/autoload.php')) {
    $loader = include_once __DIR__.'/../vendor/autoload.php';
}

require_once($yii);

$configMain = require __DIR__.'/../protected/config/main.php';
$configEnv = require __DIR__.'/../protected/config/local.php';

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV',
              (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV')
                                         : 'production'));

// To set this header in a unit test ...
// $client->setHeaders('x-http-environment-override', 'testing');

if (getenv('APPLICATION_ENV') == 'development') {
    // remove the following lines when in production mode
    defined('YII_DEBUG') or define('YII_DEBUG', true);
    // specify how many levels of call stack should be shown in each log message
    defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);
}

$config = CMap::mergeArray( $configMain, $configEnv );

Yii::createWebApplication($config)->run();