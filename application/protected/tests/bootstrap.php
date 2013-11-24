<?php

// change the following paths if necessary
$yiit=dirname(__FILE__).'/../../vendor/yiisoft/yii/framework/yiit.php';
$config=dirname(__FILE__).'/../config/test.php';

require_once($yiit);
require_once(dirname(__FILE__).'/WebTestCase.php');

// Composer autoloading
if (file_exists(__DIR__.'/../../vendor/autoload.php')) {
    $loader = include_once __DIR__.'/../../vendor/autoload.php';
}

Yii::createWebApplication($config);
