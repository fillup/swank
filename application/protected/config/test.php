<?php

return CMap::mergeArray(
    require(dirname(__FILE__) . '/main.php'), 
    array(
        'components' => array(
            'fixture' => array(
                'class' => 'system.test.CDbFixtureManager',
            ),
            'db' => array(
                'connectionString' => 'mysql:host=localhost;dbname=swank_test',
                'username' => 'swank_test',
                'password' => 'swank_test',
            ),
        ),
    )
);
