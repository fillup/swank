<?php
error_reporting(E_ALL);
ini_set('display_errors',1);

// change the following paths if necessary
$yii=__DIR__.'/../vendor/yiisoft/yii/framework/yii.php';
require_once($yii);

$mainConfig = require __DIR__.'/../protected/config/main.php';
$localConfig = require __DIR__.'/../protected/config/config.local.php';
$config = array_merge($mainConfig,$localConfig);

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

// Composer autoloading
if (file_exists(__DIR__.'/../vendor/autoload.php')) {
    $loader = include_once __DIR__.'/../vendor/autoload.php';
}

Yii::createWebApplication($config)->run();
