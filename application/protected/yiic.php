<?php

// Composer autoloading
if (file_exists(__DIR__.'/../vendor/autoload.php')) {
    $loader = include_once __DIR__.'/../vendor/autoload.php';
}

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV',
              (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV')
                                         : 'production'));

// change the following paths if necessary
$yii=dirname(__FILE__).'/../vendor/yiisoft/yii/framework/yii.php';
require_once($yii);

$configMain = require dirname(__FILE__).'/config/console.php';
$configEnv = require dirname(__FILE__).'/config/local.php';
$config = CMap::mergeArray( $configMain, $configEnv );

// change the following paths if necessary
$yiic=dirname(__FILE__).'/../vendor/yiisoft/yii/framework/yiic.php';
require_once($yiic);