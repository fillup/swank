<?php
/**
 * This is the bootstrap file for test application.
 * This file should be removed when the application is deployed for production.
 */

// change the following paths if necessary
$yii=__DIR__.'/../vendor/yiisoft/yii/framework/yii.php';
require_once($yii);

$mainConfig = require __DIR__.'/../protected/config/main.php';
$localConfig = require __DIR__.'/../protected/config/config.local.php';
$config = array_merge($mainConfig,$localConfig);

// remove the following line when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);

// Composer autoloading
if (file_exists(__DIR__.'/../vendor/autoload.php')) {
    $loader = include_once __DIR__.'/../vendor/autoload.php';
}

Yii::createWebApplication($config)->run();
