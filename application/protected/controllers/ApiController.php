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
            // Load parameters
            $name           = $req->getParam('name',false);
            $description    = $req->getParam('description', null);
            $base_path      = $req->getParam('base_path', false);
            $resource_path  = $req->getParam('resource_path', false);
            $api_version    = $req->getParam('api_version', false);
            $visibility     = $req->getParam('visibility',false);

            // Clean any beginning/ending whitespace before validation
            $name           = $name ? trim($name) : $name;
            $description    = $description ? trim($description) : $description;
            $base_path      = $base_path ? trim($base_path) : $base_path;
            $resource_path  = $resource_path ? trim($resource_path) : $resource_path;
            $api_version    = $api_version ? trim($api_version) : $api_version;
            $visibility     = $visibility ? trim($visibility) : $visibility;
            
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
                $app->visibility = $visibility;
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
            $visibility = $req->getPut('visibility', false);

            // Clean any beginning/ending whitespace before validation
            $name = $name ? trim($name) : $name;
            $description = $description ? trim($description) : $description;
            $base_path = $base_path ? trim($base_path) : $base_path;
            $resource_path = $resource_path ? trim($resource_path) : $resource_path;
            $api_version = $api_version ? trim($api_version) : $api_version;
            $visibility = $visibility ? trim($visibility) : $visibility;
            
            $application->name = $name ?: $application->name;
            $application->description = $description ?: $application->description;
            $application->base_path = $base_path ?: $application->base_path;
            $application->resource_path = $resource_path ?: $application->resource_path;
            $application->api_version = $api_version ?: $application->api_version;
            $application->visibility = $visibility ?: $application->visibility;
            if($application->save()){
                $results = array(
                    'success' => true
                );
                $this->returnJson($results, 200);
            } else {
                $e = new \Exception("Unable to update application: ".Utils::modelErrorsAsHtml($application->getErrors()),500);
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
            /**
             * Create a new API Operation
             */
            $method = $req->getParam('method', false);
            $nickname = $req->getParam('nickname', false);
            $type = $req->getParam('type',false);
            $summary = $req->getParam('summary',null);
            $notes = $req->getParam('notes',null);
            
            
            if(!$api_id){
                $e = new \Exception('Creating a new API Operation requires an api_id',400);
                $this->returnError($e);
            } elseif(!$method){
                $e = new \Exception('A method is required.',400);
                $this->returnError($e);
            } elseif(!$nickname){
                $e = new \Exception('A nickname is required.',400);
                $this->returnError($e);
            } elseif(!$method){
                $e = new \Exception('A type is required.',400);
                $this->returnError($e);
            } else {
                /**
                 * Make sure an API operation with this method doesnt already
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
                    $this->returnError($e);
                }
                /**
                 * Create new Api Operation record
                 */
                $op = new ApiOperation();
                $op->api_id = $api_id;
                $op->method = strtoupper($method);
                $op->nickname = $nickname;
                $op->type = $type;
                $op->summary = $summary;
                $op->notes = $notes;
                try{
                    if($op->save()){
                        $results = array(
                            'success' => true,
                            'status' => 200,
                            'count' => 1,
                            'data' => $op->toArray(),
                        );
                    } else {
                        $results = array(
                            'success' => false,
                            'status' => 500,
                            'errors' => Utils::modelErrorsAsArray($op->getErrors()),
                        );
                    }
                } catch (\Exception $e){
                    $results = array(
                        'success' => false,
                        'status' => 500,
                        'errors' => Utils::modelErrorsAsArray($op->getErrors()),
                    );
                }
                
                $this->returnJson($results,$results['status']);
            }
        } elseif($req->isPutRequest){
            if(!$id){
                $e = new \Exception('Api Operation ID is required to update',400);
                $this->returnError($e,400);
            }
            
            // Load parameters
            $method = $req->getPut('method', false);
            $nickname = $req->getPut('nickname', false);
            $type = $req->getPut('type',false);
            $summary = $req->getPut('summary',false);
            $notes = $req->getPut('notes',false);

            // Clean any beginning/ending whitespace before validation
            $method = $method ? trim(strtoupper($method)) : $method;
            $nickname = $nickname ? trim($nickname) : $nickname;
            $type = $type ? trim($type) : $type;
            $summary = $summary ? trim($summary) : $summary;
            $notes = $notes ? trim($notes) : $notes;
            
            $operation->method = $method ?: $operation->method;
            $operation->nickname = $nickname ?: $operation->nickname;
            $operation->type = $type ?: $operation->type;
            $operation->summary = $summary ?: $operation->summary;
            $operation->notes = $notes ?: $operation->notes;
            
            if($operation->save()){
                $results = array(
                    'success' => true
                );
                $this->returnJson($results, 200);
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
            /**
             * Create a new API Parameter
             */
            $paramType = $req->getParam('paramType', false);
            $name = $req->getParam('name', false);
            $description = $req->getParam('description',false);
            $dataType = $req->getParam('dataType',null);
            $format = $req->getParam('format',null);
            $required = $req->getParam('required',true);
            $minimum = $req->getParam('minimum',null);
            $maximum = $req->getParam('maximum',null);
            $enum = $req->getParam('enum',null);
            
            if(!$operation_id){
                $e = new \Exception('Creating a new API Parameter requires an operation_id',400);
                $this->returnError($e);
            } elseif(!$paramType || !in_array($paramType, $this->validParamTypes)){
                $e = new \Exception('A valid param type is required (path,query,body,header,form).',400);
                $this->returnError($e);
            } elseif(!$name){
                $e = new \Exception('A name is required.',400);
                $this->returnError($e);
            } elseif(!$description){
                $e = new \Exception('Description is required.',400);
                $this->returnError($e);
            } elseif(!$dataType || !in_array($dataType, array_keys($this->validDataTypes))){
                $e = new \Exception('A valid data type is required ('
                        . implode(',', array_keys($this->validDataTypes)).').',400);
                $this->returnError($e);
            } 
//            elseif(!$format){
//                $e = new \Exception('Format is required.',400);
//                $this->returnError($e);
//            } 
            else {
                /**
                 * Make sure an API operation with this method doesnt already
                 * exist.
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
                 * Additional conditional validation 
                 */
                // Flatten enum options into comma-separated string
                if(is_array($enum)){
                    $enum = implode(',', $enum);
                }
                // Check that format is valid for dataType
//                if(!in_array($format,$this->validDataTypes[$dataType])){
//                    $e = new \Exception('A valid format is required for dataType '
//                            .CHtml::encode($dataType).': '
//                            .array_values($this->validDataTypes[$dataType]),400);
//                    $this->returnError($e);
//                }
                
                /**
                 * Create new Api Parameter record
                 */
                $param = new ApiParameter();
                $param->operation_id = $operation_id;
                $param->paramType = $paramType;
                $param->name = $name;
                $param->description = $description;
                $param->dataType = $dataType;
                $param->format = $format;
                $param->required = $required;
                $param->minimum = $minimum;
                $param->maximum = $maximum;
                $param->enum = $enum;
                try{
                    if($param->save()){
                        $results = array(
                            'success' => true,
                            'status' => 200,
                            'count' => 1,
                            'data' => $param->toArray(),
                        );
                    } else {
                        $results = array(
                            'success' => false,
                            'status' => 500,
                            'errors' => Utils::modelErrorsAsArray($param->getErrors()),
                        );
                    }
                } catch (\Exception $e){
                    $results = array(
                        'success' => false,
                        'status' => 500,
                        'errors' => Utils::modelErrorsAsArray($param->getErrors()),
                    );
                }
                
                $this->returnJson($results,$results['status']);
            }
        } elseif($req->isPutRequest){
            if(!$id){
                $e = new \Exception('Api Parameter ID is required to update',400);
                $this->returnError($e,400);
            }
            
            // Load parameters
            $paramType = $req->getPut('paramType', false);
            $name = $req->getPut('name', false);
            $description = $req->getPut('description',false);
            $dataType = $req->getPut('dataType',false);
            $format = $req->getPut('format',false);
            $required = $req->getPut('required',null);
            $minimum = $req->getPut('minimum',false);
            $maximum = $req->getPut('maximum',false);
            $enum = $req->getPut('enum',false);
            
            $api_param->paramType = $paramType ?: $api_param->paramType;
            $api_param->name = $name ?: $api_param->name;
            $api_param->description = $description ?: $api_param->description;
            $api_param->dataType = $dataType ?: $api_param->dataType;
            $api_param->format = $format ?: $api_param->format;
            $api_param->required = !is_null($required) && is_bool($required) ? $required : $api_param->required;
            $api_param->minimum = $minimum ?: $api_param->minimum;
            $api_param->maximum = $maximum ?: $api_param->maximum;
            $api_param->enum = $enum ?: $api_param->enum;
            
            if($api_param->save()){
                $results = array(
                    'success' => true
                );
                $this->returnJson($results, 200);
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
