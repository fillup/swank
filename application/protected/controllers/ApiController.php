<?php

class ApiController extends Controller
{
    
    public function actionApplication($id=false)
    {
        $req = Yii::app()->request;
        $name = $req->getParam('name',false);
        $description = $req->getParam('description', null);
        $base_path = $req->getParam('base_path', false);
        $resource_path = $req->getParam('resource_path', false);
        $api_version = $req->getParam('api_version', false);
        
        // Clean any beginning/ending whitespace before validation
        $name = $name ? trim($name) : $name;
        $description = $description ? trim($description) : $description;
        $base_path = $base_path ? trim($base_path) : $base_path;
        $resource_path = $resource_path ? trim($resource_path) : $resource_path;
        $api_version = $api_version ? trim($api_version) : $api_version;
        
        if($req->isPostRequest && $id === false){
            // This is a new Application, validate all fields
            if(!$name){
                $e = new \Exception('Name is required',200);
                $this->returnError($e);
            } elseif(!$base_path){
                $e = new \Exception('Base Path does not look like a url. Must start with either http:// or https://.',200);
                $this->returnError($e);
            } elseif(!$resource_path){
                $e = new \Exception('Resource Path should be a path relative to the Base Path, for example: /api',200);
                $this->returnError($e);
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
                $app->user_id = Yii::app()->user->getId();
                if($app->save()){
                    $results = array(
                        'success' => true,
                        'application_id' => $app->id,
                    );
                    $this->returnJson($results);
                } else {
                    $e = new \Exception("Unable to create new application record: ".print_r($app->getErrors(),true),205);
                    $this->returnError($e,400);
                }
            }
        } elseif($req->isPutRequest && $id !== false) {
            $app = Application::model()->findByAttributes(array('id' => $id));
            if($app && $app->user_id == Yii::app()->user->getId()){
                $app->name = $name ?: $app->name;
                $app->description = $description ?: $app->description;
                $app->base_path = $base_path ?: $app->base_path;
                $app->resource_path = $resource_path ?: $app->resource_path;
                $app->api_version = $api_version ?: $app->api_version;
                if($app->save()){
                    $results = array(
                        'success' => true
                    );
                    $this->returnJson($results, 200);
                } else {
                    $e = new \Exception("Unable to update application: ".print_r($app->getErrors(),true),205);
                    $this->returnError($e,400);
                }
            } else {
                $e = new \Exception('Either the requested application does not exist or you do not have permission to view/edit it.',210);
                $this->returnError($e,404);
            }
        } else {
            $e = new \Exception('Invalid request',215);
            $this->returnError($e,400);
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

}
