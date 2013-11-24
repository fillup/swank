<?php

class ApiTest extends CDbTestCase
{
    public $fixtures = array(
        'users' => 'User',
    );
    
    public function __construct()
    {
        Guzzle\Http\StaticClient::mount();
    }
    
    public function testApplicationGetList()
    {
        $user = $this->users['user1'];
        echo $user['api_token'];
        $url = 'http://swank.local/api/application';
        $request = Guzzle::get($url, array(
            'query' => array('api_token' => $user['api_token'])
        ));
        $data = $request->json();
        print_r($data);
    }
}