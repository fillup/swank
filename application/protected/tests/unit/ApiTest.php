<?php

class ApiTest extends CDbTestCase
{
    public $fixtures = array(
        'users' => 'User',
        'applications' => 'Application',
        'apis' => 'Api',
        'api_operations' => 'ApiOperation',
        'api_parameters' => 'ApiParameter',
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
        $this->assertNotNull($data['data']['id']);
        
        $url .= '/'.$data['data']['id'];
        
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
    
    public function testApiGetList()
    {
        $user = $this->users['user1'];
        $application = $this->applications['app1'];
        $url = 'http://swank.local/api/api';
        $response = Guzzle::get($url, array(
            'query' => array(
                'api_token' => $user['api_token'],
                'application_id' => $application['id'],
            ),
        ));
        
        /**
         * Make sure HTTP status code is successful
         */
        $this->assertEquals(200, $response->getStatusCode());
        
        /**
         * Make sure only selected user's application apis are returned
         */
        $expectedCount = $this->getApiFixtureCountForApp($application['id']);
        $data = $response->json();
        $this->assertEquals($expectedCount, $data['count']);
    }
    
    public function testApiGetSingle()
    {
        $user = $this->users['user1'];
        $api = $this->apis['api1'];
        $url = 'http://swank.local/api/api';
        $response = Guzzle::get($url, array(
            'query' => array(
                'api_token' => $user['api_token'],
                'id' => $api['id'],
            ),
        ));
        $data = $response->json();
        
        /**
         * Make sure HTTP status code is successful
         */
        $this->assertEquals(200, $response->getStatusCode());
        
        /**
         * Make sure only one api is returned
         */
        $this->assertEquals(1,$data['count']);
        
        /**
         * Make sure api returned is one expected
         */
        $this->assertEquals($api['id'], $data['data'][0]['id']);
    }
    
    public function testApiGetListOtherUserApplication()
    {
        $user = $this->users['user1'];
        $app = $this->applications['app3'];
        $url = 'http://swank.local/api/api';
        $client = new Guzzle\Http\Client();
        $request = $client->get($url,null,array(
            'exceptions' => false,
            'query' => array(
                'api_token' => $user['api_token'],
                'application_id' => $app['id'],
            )
        ));
        $response = $request->send();
        
        /**
         * Make sure HTTP status code is not found
         */
        $this->assertEquals(404, $response->getStatusCode());
    }
    
    public function testApiGetSingleOtherUserApi()
    {
        $user = $this->users['user1'];
        $api = $this->apis['api3'];
        $url = 'http://swank.local/api/api/'.$api['id'];
        $client = new Guzzle\Http\Client();
        $request = $client->get($url,null,array(
            'exceptions' => false,
            'query' => array(
                'api_token' => $user['api_token'],
            )
        ));
        $response = $request->send();
        $data = $response->json();
        
        /**
         * Make sure result is 404 since it is invalid api id
         */
        $this->assertEquals(404, $response->getStatusCode());
    }
    
    public function testApiPostPutDelete()
    {
        $user = $this->users['user1'];
        $app = $this->applications['app1'];
        $api = array(
            'path' => '/api/{api_id}',
            'description' => 'This is an api for unit test purposes',
            'application_id' => $app['id'],
        );
        
        /**
         * First, create a new application
         */
        $url = 'http://swank.local/api/api';        
        $client = new Guzzle\Http\Client();
        $request = $client->post($url,null,$api,array(
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
        $this->assertNotNull($data['data']['id']);
        
        $url .= '/'.$data['data']['id'];
        
        /**
         * Next, lets update the created application
         */
        $updated = array(
            'description' => 'Updated via put',
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
        $this->assertTrue($data['success']);
    }
    
    public function testGetApiOperationListWithoutId()
    {
        $user = $this->users['user1'];
        
        $url = 'http://swank.local/api/apiOperation';        
        $client = new Guzzle\Http\Client();
        $request = $client->get($url,null,array(
            'exceptions' => false,
            'query' => array(
                'api_token' => $user['api_token'],
            ),
        ));
        $response = $request->send();
        $this->assertEquals(400, $response->getStatusCode());
    }
    
    public function testGetApiOperationList()
    {
        $user = $this->users['user1'];
        $api = $this->apis['api1'];
        
        $url = 'http://swank.local/api/apiOperation';        
        $client = new Guzzle\Http\Client();
        $request = $client->get($url,null,array(
            'exceptions' => false,
            'query' => array(
                'api_token' => $user['api_token'],
                'api_id'    => $api['id'],
            ),
        ));
        $response = $request->send();
        $data = $response->json();
        $this->assertEquals(2, $data['count']);
    }
    
    public function testGetSingleApiOperation()
    {
        $user = $this->users['user1'];
        $api = $this->apis['api1'];
        $op = $this->api_operations['op1'];
        
        $url = 'http://swank.local/api/apiOperation/'.$op['id'];
        $client = new Guzzle\Http\Client();
        $request = $client->get($url,null,array(
            'exceptions' => false,
            'query' => array(
                'api_token' => $user['api_token'],
            ),
        ));
        $response = $request->send();
        $data = $response->json();
        
        $this->assertEquals(1,$data['count']);
        $this->assertEquals($op['id'], $data['data'][0]['id']);
    }
    
    public function testCreateUpdateDeleteApiOperation()
    {
        $user = $this->users['user1'];
        $api = $this->apis['api1'];
        
        $operation = array(
            'api_id' => $api['id'],
            'method' => 'PUT',
            'nickname' => 'updateApiOperation',
            'type' => 'testomg',
            'summary' => 'this is the summary',
            'notes' => 'these are some notes',
        );
        
        /**
         * First lets create the Api Operation
         */
        $url = 'http://swank.local/api/apiOperation';
        $client = new Guzzle\Http\Client();
        $request = $client->post($url,null,$operation,array(
            'exceptions' => false,
            'query' => array(
                'api_token' => $user['api_token'],
            ),
        ));
        $response = $request->send();
        $data = $response->json();
        if($response->getStatusCode() != 200){
            print_r($data);
        }
        $this->assertEquals(1,$data['count']);
        $this->assertNotNull($data['data']['id']);
        
        /**
         * Now lets update it
         */
        $url .= '/'.$data['data']['id'];
        $update = array(
            'summary' => 'updating the summary',
        );
        $request = $client->put($url, null, $update, array(
            'exceptions' => false,
            'query' => array(
                'api_token' => $user['api_token'],
            ),
        ));
        $response = $request->send();
        if($response->getStatusCode() != 200){
            print_r($response->getBody(true));
        }
        $this->assertEquals(200,$response->getStatusCode());
        
        /**
         * Finally lets delete the api operation
         */
        $request = $client->delete($url, null, null, array(
            'exceptions' => false,
            'query' => array(
                'api_token' => $user['api_token'],
            ),
        ));
        $response = $request->send();
        if($response->getStatusCode() != 200){
            print_r($response->getBody(true));
        }
        $this->assertEquals(200,$response->getStatusCode());
    }
    
    public function testGetApiOperationOtherUser()
    {
        $user = $this->users['user1'];
        $api = $this->apis['api3'];
        
        $url = 'http://swank.local/api/apiOperation';        
        $client = new Guzzle\Http\Client();
        $request = $client->get($url,null,array(
            'exceptions' => false,
            'query' => array(
                'api_token' => $user['api_token'],
                'api_id'    => $api['id'],
            ),
        ));
        $response = $request->send();
        $data = $response->json();
        $this->assertEquals(404, $response->getStatusCode());
    }
    
    public function testDeleteApiOperationOtherUser()
    {
        $user = $this->users['user1'];
        $op = $this->api_operations['op3'];
        
        $url = 'http://swank.local/api/apiOperation/'.$op['id'];        
        $client = new Guzzle\Http\Client();
        $request = $client->delete($url,null,null,array(
            'exceptions' => false,
            'query' => array(
                'api_token' => $user['api_token'],
            ),
        ));
        $response = $request->send();
        $data = $response->json();
        $this->assertEquals(404, $response->getStatusCode());
    }
    
    public function testGetParameterList()
    {
        $user = $this->users['user1'];
        $op = $this->api_operations['op1'];
        
        $url = 'http://swank.local/api/apiParameter';        
        $client = new Guzzle\Http\Client();
        $request = $client->get($url,null,array(
            'exceptions' => false,
            'query' => array(
                'api_token' => $user['api_token'],
                'operation_id'    => $op['id'],
            ),
        ));
        $response = $request->send();
        $data = $response->json();
        $this->assertEquals(2, $data['count']);
    }
    
    public function testGetSingleParameterOtherUser()
    {
        $user = $this->users['user1'];
        $api_param = $this->api_parameters['param3'];
        
        $url = 'http://swank.local/api/apiParameter/'.$api_param['id'];
        $client = new Guzzle\Http\Client();
        $request = $client->get($url,null,array(
            'exceptions' => false,
            'query' => array(
                'api_token' => $user['api_token'],
            ),
        ));
        
        $response = $request->send();
        $data = $response->json();
        if($response->getStatusCode() != 404){
            print_r($data);
        }
        $this->assertEquals(404, $response->getStatusCode());
    }
    
    public function testParameterCreate()
    {
        $user = $this->users['user1'];
        $op = $this->api_operations['op1'];
        
        $url = 'http://swank.local/api/apiParameter';
        $client = new Guzzle\Http\Client();
        
        /**
         * First test creating a parameter
         */
        $parameter = array(
            'operation_id' => $op['id'],
            'paramType' => 'query',
            'name' => 'userName',
            'description' => 'User\'s username',
            'dataType' => 'string',
            'format' => 'string',
            'required' => true
        );
        $request = $client->post($url,null,$parameter,array(
            'exceptions' => false,
            'query' => array(
                'api_token' => $user['api_token'],
            ),
        ));
        
        $response = $request->send();
        $data = $response->json();
        if($response->getStatusCode() != 200){
            print_r($data);
        }
        $this->assertEquals(200, $response->getStatusCode());
        
        /**
         * Now update the parameter
         */
        $url .= '/'.$data['data']['id'];
        $update = array(
            'description' => 'updating the description',
        );
        $request = $client->put($url, null, $update, array(
            'exceptions' => false,
            'query' => array(
                'api_token' => $user['api_token'],
            ),
        ));
        $response = $request->send();
        if($response->getStatusCode() != 200){
            print_r($response->getBody(true));
        }
        $this->assertEquals(200,$response->getStatusCode());
        
        /**
         * Now lets delete the parameter
         */
        $request = $client->delete($url, null, null, array(
            'exceptions' => false,
            'query' => array(
                'api_token' => $user['api_token'],
            ),
        ));
        $response = $request->send();
        if($response->getStatusCode() != 200){
            print_r($response->getBody(true));
        }
        $this->assertEquals(200,$response->getStatusCode());
        
        /**
         * Now try to fetch original parameter and ensure we get a 404
         */
        $request = $client->get($url,null,array(
            'exceptions' => false,
            'query' => array(
                'api_token' => $user['api_token'],
            ),
        ));
        
        $response = $request->send();
        $data = $response->json();
        if($response->getStatusCode() != 404){
            print_r($data);
        }
        $this->assertEquals(404, $response->getStatusCode());
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
    
    public function getApiFixtureCountForApp($application_id)
    {
        $count = 0;
        
        foreach($this->apis as $api){
            if($api['application_id'] == $application_id){
                $count++;
            }
        }
        
        return $count;
    }
}