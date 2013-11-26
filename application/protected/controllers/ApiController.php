<?php

class ApiController extends Controller
{   
    /**
     * After validateApiToken filter is run, $_user will store the
     * authenticated user based on api_token
     * @var User
     */
    private $_user;
    
    public function filters()
    {
        return array(
            'validateApiToken',
        );
    }
    
    public function actionApplication($id=false)
    {
        $req = Yii::app()->request;
        /**
         * If user is working with speicific application, make sure they own it
         */
        if($id){
            $application = Application::model()->findByPk($id);
            if(!$application || $application->user_id != $this->_user->id){
                $e = new \Exception('Invalid Application ID',404);
                $this->returnError($e,404);
            }
        }
        
        if(strtoupper($req->requestType) == 'GET'){
            /**
             * List application(s)
             */
            if($id){
                $results = array(
                    'success' => true,
                    'status' => 200,
                    'count' => 1,
                    'data' => $application->toArray(),
                );
                $this->returnJson($results);
            } else {
                $apps = Application::model()->findAllByAttributes(array('user_id' => $this->_user->id));
                if($apps){
                    $results = array(
                        'success' => true,
                        'status' => 200,
                        'count' => count($apps),
                        'data' => array(),
                    );
                    foreach($apps as $app){
                        $results['data'][] = $app->toArray();
                    }
                    $this->returnJson($results);
                } else {
                    $results = array(
                        'success' => true,
                        'status' => 200,
                        'count' => 0,
                        'data' => array(),
                    );
                    $this->returnJson($results,200);
                }
            }
        } elseif($req->isPostRequest && $id === false){
            // Load parameters
            $name           = $req->getParam('name',false);
            $description    = $req->getParam('description', null);
            $base_path      = $req->getParam('base_path', false);
            $resource_path  = $req->getParam('resource_path', false);
            $api_version    = $req->getParam('api_version', false);

            // Clean any beginning/ending whitespace before validation
            $name           = $name ? trim($name) : $name;
            $description    = $description ? trim($description) : $description;
            $base_path      = $base_path ? trim($base_path) : $base_path;
            $resource_path  = $resource_path ? trim($resource_path) : $resource_path;
            $api_version    = $api_version ? trim($api_version) : $api_version;
            
            // This is a new Application, validate all fields
            if(!$name){
                $e = new \Exception('Name is required',400);
                $this->returnError($e,400);
            } elseif(!$base_path){
                $e = new \Exception('Base Path does not look like a url. Must start with either http:// or https://.',400);
                $this->returnError($e,400);
            } elseif(!$resource_path){
                $e = new \Exception('Resource Path should be a path relative to the Base Path, for example: /api',400);
                $this->returnError($e,400);
            } else {
                if(substr($base_path,-1) == '/'){
                    $base_path = substr($base_path, 0, -1);
                }
                $app = new Application();
                $app->name = $name;
                $app->description = $description;
                $app->api_version = $api_version;
                $app->base_path = $base_path;
                $app->resource_path = $resource_path;
                $app->user_id = $this->_user->id;
                if($app->save()){
                    $results = array(
                        'success' => true,
                        'status' => 200,
                        'count' => 1,
                        'data' => $app->toArray(),
                    );
                    $this->returnJson($results,200);
                } else {
                    $e = new \Exception("Unable to create new application record: ".print_r($app->getErrors(),true),205);
                    $this->returnError($e,400);
                }
            }
        } elseif($req->isPutRequest) {
            if(!$id){
                $e = new \Exception('Application ID is required to update',400);
                $this->returnError($e);
            }
            
            // Load parameters
            $name = $req->getPut('name',false);
            $description = $req->getPut('description', null);
            $base_path = $req->getPut('base_path', false);
            $resource_path = $req->getPut('resource_path', false);
            $api_version = $req->getPut('api_version', false);

            // Clean any beginning/ending whitespace before validation
            $name = $name ? trim($name) : $name;
            $description = $description ? trim($description) : $description;
            $base_path = $base_path ? trim($base_path) : $base_path;
            $resource_path = $resource_path ? trim($resource_path) : $resource_path;
            $api_version = $api_version ? trim($api_version) : $api_version;
            
            $application->name = $name ?: $application->name;
            $application->description = $description ?: $application->description;
            $application->base_path = $base_path ?: $application->base_path;
            $application->resource_path = $resource_path ?: $application->resource_path;
            $application->api_version = $api_version ?: $application->api_version;
            if($application->save()){
                $results = array(
                    'success' => true
                );
                $this->returnJson($results, 200);
            } else {
                $e = new \Exception("Unable to update application: ".Utils::modelErrorsAsArray($application->getErrors()),500);
                $this->returnError($e);
            }
            
        } elseif(strtoupper($req->requestType) == 'DELETE'){
            if(!$id){
                $e = new \Exception('Application ID is required to update',400);
                $this->returnError($e);
            }
            
            if($application->delete()){
                $results = array(
                    'success' => true
                );
                $this->returnJson($results, 200);
            } else {
                $e = new \Exception("Unable to delete application: ".Utils::modelErrorsAsArray($application->getErrors()),500);
                $this->returnError($e);
            }
        } else {
            $e = new \Exception('Not a supported method. Supported methods are GET, POST, PUT, DELETE.',215);
            $this->returnError($e,405);
        }
        
    }
    
    public function actionApi($id=false)
    {
        $req = Yii::app()->request;
        
        /**
         * Load application model and validate user owns it
         */
        $application_id = $req->getParam('application_id',false);
        if($application_id){
            $application = Application::model()->findByPk($application_id);
            if(!$application || $application->user_id != $this->_user->id){
                $e = new \Exception('Invalid Application ID',404);
                $this->returnError($e,404);
            } 
        }
        
        /**
         * If API ID provided, make sure user can edit it
         */
        if($id){
            $api = Api::model()->findByPk($id);
            if(!$api || $api->application->user_id != $this->_user->id){
                $e = new \Exception('Invalid Api ID',404);
                $this->returnError($e,404);
            }
        }
        
        /**
         * Get request should list APIs, either all for the requested
         * application or only the one if $id is specified
         */
        if(strtoupper($req->requestType) == 'GET'){
            $attributes = array();
            if($id){
                $attributes['id'] = $id;
            } elseif($application_id){
                $attributes['application_id'] = $application_id;
            } else {
                $e = new \Exception('Listing APIs requires an application_id or an api_id',400);
                $this->returnError($e);
            }
            $data = array();
            $apis = Api::model()->findAllByAttributes($attributes);
            if($apis){
                foreach($apis as $api){
                    if($api->application->user_id == $this->_user->id){
                        $data[] = $api->toArray();
                    }
                }
                
            }
            
            $results = array(
                'success' => true,
                'status' => 200,
                'count' => count($data),
                'data' => $data,
            );
            
            $this->returnJson($results,$results['status']);
        } elseif ($req->isPostRequest){
            $path = $req->getParam('path',false);
            $description = $req->getParam('description',null);
            
            if(!$application_id){
                $e = new \Exception('Creating a new API requires an application_id',400);
                $this->returnError($e);
            } elseif(!$path){
                $e = new \Exception('A path is required.',400);
                $this->returnError($e);
            } else {
                /**
                 * Create new API record
                 */
                $api = new Api();
                $api->application_id = $application_id;
                $api->path = $path;
                $api->description = $description;
                try{
                    if($api->save()){
                        $results = array(
                            'success' => true,
                            'status' => 200,
                            'count' => 1,
                            'data' => $api->toArray(),
                        );
                    } else {
                        $results = array(
                            'success' => false,
                            'status' => 500,
                            'errors' => Utils::modelErrorsAsArray($api->getErrors()),
                        );
                    }
                } catch (\Exception $e){
                    $results = array(
                        'success' => false,
                        'status' => 500,
                        'errors' => Utils::modelErrorsAsArray($api->getErrors()),
                    );
                }
                
                $this->returnJson($results,$results['status']);
            }
        } elseif ($req->isPutRequest){
            if(!$id){
                $e = new \Exception('Api ID is required to update',400);
                $this->returnError($e,400);
            }
            
            // Load parameters
            $description = $req->getPut('description', null);
            $path = $req->getPut('path', false);

            // Clean any beginning/ending whitespace before validation
            $description = $description ? trim($description) : $description;
            $path = $path ? trim($path) : $path;
            
            $api->description = $description ?: $api->description;
            $api->path = $path ?: $api->path;
            if($api->save()){
                $results = array(
                    'success' => true
                );
                $this->returnJson($results, 200);
            } else {
                $e = new \Exception("Unable to update api: ".Utils::modelErrorsAsArray($api->getErrors()),500);
                $this->returnError($e,500);
            }
        } elseif ($req->isDeleteRequest){
            if(!$id){
                $e = new \Exception('Api ID is required to update',400);
                $this->returnError($e,400);
            }
            
            if($api->delete()){
                $results = array(
                    'success' => true
                );
                $this->returnJson($results, 200);
            } else {
                $e = new \Exception("Unable to delete api: ".Utils::modelErrorsAsArray($application->getErrors()),500);
                $this->returnError($e);
            }
        } else {
            $e = new \Exception('Invalid request method', 405);
            $this->returnError($e, 405);
        }
    }

    public function returnJson($data, $status = 200)
    {
        // Set the content type header.
        header('Content-type: applicaton/json', true, $status);

        // Output the JSON data.
        echo json_encode($data);

        Yii::app()->end();
    }

    public function returnError($error, $status = 400)
    {
        $data = array(
            'success' => 'false',
            'error' => $error->getMessage(),
            'code' => $error->getCode(),
        );

        $this->returnJson($data, $status);
    }
    
    public function filterValidateApiToken($filterChain)
    {
        /**
         * Validate API token before continuing
         */
        $api_token = Yii::app()->request->getParam('api_token',false);
        if($api_token){
            $user = User::model()->findByAttributes(array('api_token' => $api_token));
        } else {
            $user = false;
        }
        if(!$user){
            $e = new \Exception('Invalid API Token',403);
            $this->returnError($e,403);
        } else {
            $this->_user = $user;
            $filterChain->run();
        }
    }

}
