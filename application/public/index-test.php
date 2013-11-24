<?php
/**
 * This is the bootstrap file for test application.
 * This file should be removed when the application is deployed for production.
 */

// change the following paths if necessary
$yii=dirname(__FILE__).'/../vendor/yiisoft/yii/framework/yii.php';

// remove the following line when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);

// Composer autoloading
if (file_exists(__DIR__.'/../vendor/autoload.php')) {
    $loader = include_once __DIR__.'/../vendor/autoload.php';
}

require_once($yii);

$configMain = require dirname(__FILE__).'/../protected/config/main.php';
$configEnv = require __DIR__.'/../protected/config/local.php';

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV',
              (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV')
                                         : 'production'));

if(getenv('APPLICATION_ENV') == 'development'){
    // remove the following lines when in production mode
    defined('YII_DEBUG') or define('YII_DEBUG',true);
    // specify how many levels of call stack should be shown in each log message
    defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);
}

$config = CMap::mergeArray( $configMain, $configEnv );

Yii::createWebApplication($config)->run();