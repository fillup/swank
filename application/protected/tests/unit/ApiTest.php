<?php

class ApiTest extends CDbTestCase
{
    public $fixtures = array(
        'users' => 'User',
        'applications' => 'Application',
        'apis' => 'Api',
    );
    
    public static function setUpBeforeClass()
    {
        Guzzle\Http\StaticClient::mount();
    }
    
    public function testApplicationGetList()
    {
        $user = $this->users['user1'];
        $url = 'http://swank.local/api/application';
        $response = Guzzle::get($url, array(
            'query' => array('api_token' => $user['api_token'])
        ));
        
        /**
         * Make sure HTTP status code is successful
         */
        $this->assertEquals(200, $response->getStatusCode());
        
        /**
         * Make sure only selected user's applications are returned
         */
        $expectedCount = $this->getAppFixtureCountForUser($user['id']);
        $data = $response->json();
        $this->assertEquals($expectedCount, $data['count']);
    }
    
    public function testApplicationGetSingle()
    {
        $user = $this->users['user1'];
        $application = $this->applications['app1'];
        $url = 'http://swank.local/api/application';
        $response = Guzzle::get($url, array(
            'query' => array(
                'api_token' => $user['api_token'],
                'id' => $application['id'],
            )
        ));
        $data = $response->json();
        
        /**
         * Make sure only one result is returned
         */
        $this->assertEquals(1, $data['count']);
        
        /**
         * Make sure it is the application expected
         */
        $this->assertEquals($application['id'], $data['data']['id']);
    }
    
    public function testApplicationGetSingleOtherUserFails()
    {
        $user = $this->users['user1'];
        $application = $this->applications['app3'];
        $url = 'http://swank.local/api/application';
        try{
            $response = Guzzle::get($url, array(
                'query' => array(
                    'api_token' => $user['api_token'],
                    'id' => $application['id'],
                )
            ));
        } catch (\Exception $e){
            /**
            * Make sure response code is 404
            */
           $this->assertEquals(404, $e->getResponse()->getStatusCode());
        }
    }
    
    public function testApplicationPostPutDelete()
    {
        $user = $this->users['user1'];
        $app = array(
            'name' => 'Unit test application - '.microtime(),
            'description' => 'This is an application for unit test purposes',
            'base_path' => 'http://swank.local/',
            'resource_path' => '/api',
            'api_version' => '1.0',
        );
        
        /**
         * First, create a new application
         */
        $url = 'http://swank.local/api/application';        
        $client = new Guzzle\Http\Client();
        $request = $client->post($url,null,$app,array(
            'exceptions' => false,
            'query' => array(
                'api_token' => $user['api_token'],
            )
        ));
        $response = $request->send();
        $data = $response->json();
        /**
         * Make sure response is successful
         */
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertNotNull($data['id']);
        
        $url .= '/'.$data['id'];
        
        /**
         * Next, lets update the created application
         */
        $updated = array(
            'name' => 'Unit test application updated - '.microtime(),
            'description' => 'Updated via put',
            'api_version' => '1.1',
        );
        $request = $client->put($url,null,$updated,array(
            'exceptions' => false,
            'query' => array(
                'api_token' => $user['api_token'],
            )
        ));
        $response = $request->send();
        $data = $response->json();
        
        /**
         * Make sure response is successful
         */
        if($response->getStatusCode() != 200){
            print_r($data);
        }
        $this->assertEquals(200, $response->getStatusCode());

        /**
         * Finally, delete the created application
         */
        $request = $client->delete($url,null,null,array(
            'exceptions' => false,
            'query' => array(
                'api_token' => $user['api_token'],
            )
        ));
        $response = $request->send();
        $data = $response->json();
        
        if($response->getStatusCode() != 200){
            print_r($data);
        }
        $this->assertEquals(200, $response->getStatusCode());
    }
    
    public function getAppFixtureCountForUser($user_id)
    {
        $count = 0;
        foreach($this->applications as $app){
            if($app['user_id'] == $user_id){
                $count++;
            }
        }
        
        return $count;
    }
}