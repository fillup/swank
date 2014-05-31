<?php

class ApiController extends Controller
{   
    /**
     * After validateApiToken filter is run, $_user will store the
     * authenticated user based on api_token
     * @var User
     */
    private $_user;
    
    /**
     * List of valid paramType options, for use in actionApiParameter mainly
     * @var array
     */
    public $validParamTypes = array('path','query','body','header','form');
    
    /**
     * List of valid data types and formats
     * @var array
     */
    public $validDataTypes = array(
        'integer'   => array('integer','int32'),
        'long'      => array('integer','int64'),
        'float'     => array('number','float'),
        'double'    => array('number','double'),
        'string'    => array('string'),
        'byte'      => array('string','byte'),
        'boolean'   => array('boolean'),
        'date'      => array('string','date'),
        'dateTime'  => array('string','date-time'),
    );
    
    public $validVisibilityOptions = array('public','unlisted');
    
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
             * Show single application
             */
            if($id){
                /**
                 * Swagger param indicates whether it should only return
                 * swagger api definition
                 */
                $swagger = $req->getParam('swagger',false);
                if($swagger){
                    $spec = $application->toSwagger();
                    $this->returnJson($spec);
                } else {
                    $results = array(
                        'success' => true,
                        'status' => 200,
                        'count' => 1,
                        'data' => $application->toArray(),
                    );
                    $this->returnJson($results);
                }
            } else {
                /**
                * List application(s)
                */
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

            $app = new Application();
            $app->user_id = $this->_user->id;
            $app->attributes=$_POST;
            if($app->save()){
                $results = array(
                    'success' => true,
                    'status' => 200,
                    'count' => 1,
                    'data' => $app->toArray(),
                );
                $this->returnJson($results,200);
            } else {
                $e = new \Exception("Unable to create new application record: ".Utils::modelErrorsAsHtml($app->getErrors(),true),205);
                $this->returnError($e,400);
            }

        } elseif($req->isPutRequest) {
            if(!$id){
                $e = new \Exception('Application ID is required to update',400);
                $this->returnError($e);
            }
            
            $application->attributes=$this->getPutVars();

            if($application->save()){
                $results = array();
                $this->returnJson($results, 204);
            } else {
                $e = new \Exception("Unable to update application: ".Utils::modelErrorsAsHtml($application->getErrors()),500);
                $this->returnError($e);
            }
            
        } elseif(strtoupper($req->requestType) == 'DELETE'){
            if(!$id){
                $e = new \Exception('Application ID is required to delete',400);
                $this->returnError($e);
            }
            
            if($application->delete()){
                $results = array(
                    'success' => true
                );
                $this->returnJson($results, 200);
            } else {
                $e = new \Exception("Unable to delete application: ".Utils::modelErrorsAsHtml($application->getErrors()),500);
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
            

            $api = new Api();
            $api->attributes = $_POST;

            if($api->save()){
                $results = array(
                    'success' => true,
                    'status' => 200,
                    'count' => 1,
                    'data' => $api->toArray(),
                );
                $this->returnJson($results,$results['status']);
            } else {
                $e = new \Exception("Unable to create api: ".Utils::modelErrorsAsHtml($api->getErrors()),500);
                $this->returnError($e,500);
            }
        } elseif ($req->isPutRequest){
            if(!$id){
                $e = new \Exception('Api ID is required to update',400);
                $this->returnError($e,400);
            }
            
            $api->attributes = $this->getPutVars();
            if($api->save()){
                $results = array();
                $this->returnJson($results, 204);
            } else {
                $e = new \Exception("Unable to update api: ".Utils::modelErrorsAsHtml($api->getErrors()),500);
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
                $e = new \Exception("Unable to delete api: ".Utils::modelErrorsAsHtml($api->getErrors()),500);
                $this->returnError($e);
            }
        } else {
            $e = new \Exception('Invalid request method', 405);
            $this->returnError($e, 405);
        }
    }
    
    public function actionApiOperation($id=false)
    {
        $req = Yii::app()->request;
        
        /**
         * Load api model and validate user owns it
         */
        $api_id = $req->getParam('api_id',false);
        if($api_id){
            $api = Api::model()->findByPk($api_id);
            if(!$api || $api->application->user_id != $this->_user->id){
                $e = new \Exception('Invalid Api ID',404);
                $this->returnError($e,404);
            }
        }
        
        /**
         * If ApiOperation ID provided, make sure user can edit it
         */
        if($id){
            $operation = ApiOperation::model()->findByPk($id);
            if(!$operation || $operation->api->application->user_id != $this->_user->id){
                $e = new \Exception('Invalid Api Operation ID',404);
                $this->returnError($e,404);
            }
        }
        
        /**
         * For get requests, if ID is not provided return a list of 
         * operations for given api_id. If ID is provided, only return
         * details for given api operation
         */
        if(strtoupper($req->requestType) == 'GET'){
            $attributes = array();
            if($id){
                $attributes['id'] = $id;
            } elseif($api_id){
                $attributes['api_id'] = $api_id;
            } else {
                $e = new \Exception('Listing Api Operations requires an api_id or an api_operation_id',400);
                $this->returnError($e);
            }
            $data = array();
            $operations = ApiOperation::model()->findAllByAttributes($attributes);
            if($operations){
                foreach($operations as $operation){
                    if($operation->api->application->user_id == $this->_user->id){
                        $data[] = $operation->toArray();
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
        } elseif($req->isPostRequest){
            $method = $req->getParam('method',false);
            /**
             * Make sure an API operation with this method doesn't already
             * exist.
             */
            $check = ApiOperation::model()->findByAttributes(array(
                'api_id' => $api_id,
                'method' => strtoupper($method),
            ));
            if($check){
                $e = new \Exception('An API operation with method '
                        .CHtml::encode(strtoupper($method))
                        .' for this API already exists, nickname: '
                        .$check->nickname);
                $this->returnError($e,400);
            }
            /**
             * Create new Api Operation record
             */
            $op = new ApiOperation();
            $op->attributes = $_POST;

            if($op->save()){
                $results = array(
                    'success' => true,
                    'status' => 200,
                    'count' => 1,
                    'data' => $op->toArray(),
                );
                $this->returnJson($results,200);
            } else {
                $e = new \Exception("Unable to create api operation: ".Utils::modelErrorsAsHtml($op->getErrors()),400);
                $this->returnError($e,400);
            }
        } elseif($req->isPutRequest){
            if(!$id){
                $e = new \Exception('Api Operation ID is required to update',400);
                $this->returnError($e,400);
            }
            
            $operation->attributes = $this->getPutVars();
            
            if($operation->save()){
                $results = array();
                $this->returnJson($results, 204);
            } else {
                $e = new \Exception("Unable to update api operation: ".Utils::modelErrorsAsHtml($operation->getErrors()),400);
                $this->returnError($e,400);
            }
        } elseif($req->isDeleteRequest) {
            if(!$id){
                $e = new \Exception('Api Operation ID is required to delete',400);
                $this->returnError($e,400);
            }
            
            if($operation->delete()){
                $results = array(
                    'success' => true
                );
                $this->returnJson($results, 200);
            } else {
                $e = new \Exception("Unable to delete api operation: ".Utils::modelErrorsAsHtml($operation->getErrors()),400);
                $this->returnError($e);
            }
        } else {
            $e = new \Exception('Invalid request method', 405);
            $this->returnError($e, 405);
        }
    }
    
    public function actionApiParameter($id=false)
    {
        $req = Yii::app()->request;
        
        /**
         * Load api model and validate user owns it
         */
        $operation_id = $req->getParam('operation_id',false);
        if($operation_id){
            $op = ApiOperation::model()->findByPk($operation_id);
            if(!$op || $op->api->application->user_id != $this->_user->id){
                $e = new \Exception('Invalid Api Operation ID',404);
                $this->returnError($e,404);
            }
        }
        
        /**
         * If ApiParameter ID provided, make sure user can edit it
         */
        if($id){
            $api_param = ApiParameter::model()->findByPk($id);
            if(!$api_param || $api_param->operation->api->application->user_id != $this->_user->id){
                $e = new \Exception('Invalid Api Parameter ID',404);
                $this->returnError($e,404);
            }
        }
        
        /**
         * For get requests, if ID is not provided return a list of 
         * parameters for given api_operation_id. If ID is provided, only return
         * details for given api operation
         */
        if(strtoupper($req->requestType) == 'GET'){
            $attributes = array();
            if($id){
                $attributes['id'] = $id;
            } elseif($operation_id){
                $attributes['operation_id'] = $operation_id;
            } else {
                $e = new \Exception('Listing Api Parameters requires an id or an operation_id',400);
                $this->returnError($e);
            }
            $data = array();
            $params = ApiParameter::model()->findAllByAttributes($attributes);
            if($params){
                foreach($params as $api_param){
                    if($api_param->operation->api->application->user_id == $this->_user->id){
                        $data[] = $api_param->toArray();
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
        } elseif($req->isPostRequest){
            $name = $req->getParam('name', false);

            /**
             * Make sure this is not a duplicate parameter for the same operation
             */
            $check = ApiParameter::model()->findByAttributes(array(
                'operation_id' => $operation_id,
                'name' => $name,
            ));
            if($check){
                $e = new \Exception('An API parameter with name '
                        .CHtml::encode($name)
                        .' for this API Operation ('
                        .CHtml::encode($check->operation->nickname)
                        .') already exists for method:'. CHtml::encode($check->operation->method));
                $this->returnError($e);
            }

                
                /**
                 * Create new Api Parameter record
                 */
                $param = new ApiParameter();
                $param->attributes = $_POST;
                if($param->save()){
                    $results = array(
                        'success' => true,
                        'status' => 200,
                        'count' => 1,
                        'data' => $param->toArray(),
                    );
                    $this->returnJson($results,$results['status']);
                } else {
                    $e = new \Exception("Unable to create api parameter: ".Utils::modelErrorsAsHtml($param->getErrors()),500);
                    $this->returnError($e,500);
                }
        } elseif($req->isPutRequest){
            if(!$id){
                $e = new \Exception('Api Parameter ID is required to update',400);
                $this->returnError($e,400);
            }
            
            $api_param->attributes = $this->getPutVars();
            
            if($api_param->save()){
                $results = array();
                $this->returnJson($results, 204);
            } else {
                $e = new \Exception("Unable to update api parameter: ".Utils::modelErrorsAsHtml($api_param->getErrors()),500);
                $this->returnError($e,500);
            }
        } elseif($req->isDeleteRequest) {
            if(!$id){
                $e = new \Exception('Api Parameter ID is required to delete',400);
                $this->returnError($e,400);
            }
            
            if($api_param->delete()){
                $results = array(
                    'success' => true
                );
                $this->returnJson($results, 200);
            } else {
                $e = new \Exception("Unable to delete api parameter: ".Utils::modelErrorsAsHtml($api_param->getErrors()),500);
                $this->returnError($e);
            }
        } else {
            $e = new \Exception('Invalid request method', 405);
            $this->returnError($e, 405);
        }
        
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
            if(!Yii::app()->user->isGuest){
                $user = Yii::app()->user->user;
            } else {
                $user = false;
            }
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
