<?php

return array(
    'components' => array(
        'modules' => array(
            'gii' => array(
                'class' => 'system.gii.GiiModule',
                'password' => 'SwankyP@ss!',

                // If removed, Gii defaults to localhost only. Edit carefully to
                // taste.
                'ipFilters' => array('127.0.0.1', '::1','192.168.*.*'),
            ),
        ),
        'db' => array(
//            'connectionString' => 'mysql:host=localhost;dbname=swank',
//            'username' => 'swank',
//            'password' => 'swank',
            'connectionString' => 'mysql:host=localhost;dbname=swank_test',
            'username' => 'swank_test',
            'password' => 'swank_test',
        ),
    ),
);