<?php

class GenController extends Controller {

    public function actionIndex($id=false) {
        $this->menu = array(
//            array('label' => 'Define Application', 'url' => '#application'),
//            array('label' => 'Define APIs', 'url' => '#apis'),
//            array('label' => 'Define Operations', 'url' => '#operations'),
//            array('label' => 'Define Parameters', 'url' => '#Parameters'),
//            array('label' => 'Define Responses', 'url' => '#responses'),
        );
        
        if($id){
            $application = Application::model()->findByPk($id);
            if(!$application || $application->user_id != Yii::app()->user->getId()){
                Yii::app()->user->setFlash('danger','Invalid application ID provided.');
                $this->redirect('/gen');
            }
        } else {
            $application = new Application();
        }
        
        $this->render('index', array(
            'application' => $application,
        ));
    }
    
    public function actionGetEditApiForm($id=false)
    {
        $this->layout = '';
        $found = false;
        
        $application_id = Yii::app()->request->getParam('application_id',false);
        
        $title = "Create API";
        if($id){
            $api = Api::model()->findByPk($id);
            if($api && $api->application->user_id == Yii::app()->user->getId()){
                $found = $api;
                $title = "Update API";
                $application_id = $api->application_id;
            }
        } else {
            $id = 'NEW';
        }
        if(!$found){
            $found = new Api();
            $found->application_id = $application_id;
        }
        
        $form = $this->renderPartial('/partials/api-form',array(
            'api' => $found,
            'id' => $id,
        ),true);
        
        $modal = $this->renderPartial('/partials/modal',array(
            'id' => $id,
            'title' => $title,
            'body' => $form,
            'saveable' => true,
            'saveAction' => "updateApi('$id')",
        ),true);
        
        echo $modal;
    }
    
    public function actionGetEditOperationForm($id=false)
    {
        $this->layout = '';
        $found = false;
        
        $api_id = Yii::app()->request->getParam('api_id',false);
        
        $title = "Create Operation";
        if($id){
            $operation = ApiOperation::model()->findByPk($id);
            if($operation && $operation->api->application->user_id == Yii::app()->user->getId()){
                $found = $operation;
                $title = "Update Operation";
                $api_id = $operation->api_id;
            }
        } else {
            $id = 'NEW';
        }
        if(!$found){
            $found = new ApiOperation();
            $found->api_id = $api_id;
        }
        
        $form = $this->renderPartial('/partials/operation-form',array(
            'operation' => $found,
            'id' => $id,
        ),true);
        
        $modal = $this->renderPartial('/partials/modal',array(
            'id' => $id,
            'title' => $title,
            'body' => $form,
            'saveable' => true,
            'saveAction' => "updateOperation('$id')",
        ),true);
        
        echo $modal;
    }
    
    public function actionGetEditParameterForm($id=false)
    {
        $this->layout = '';
        $found = false;
        
        $operation_id = Yii::app()->request->getParam('operation_id',false);
        
        $title = "Create Parameter";
        if($id){
            $parameter = ApiParameter::model()->findByPk($id);
            if($parameter && $parameter->operation->api->application->user_id == Yii::app()->user->getId()){
                $found = $parameter;
                $title = "Update Parameter";
                $operation_id = $parameter->operation_id;
            }
        } else {
            $id = 'NEW';
        }
        if(!$found){
            $found = new ApiParameter();
            $found->operation_id = $operation_id;
        }
        
        $form = $this->renderPartial('/partials/parameter-form',array(
            'parameter' => $found,
            'id' => $id,
        ),true);
        
        $modal = $this->renderPartial('/partials/modal',array(
            'id' => $id,
            'title' => $title,
            'body' => $form,
            'saveable' => true,
            'saveAction' => "updateParameter('$id')",
        ),true);
        
        echo $modal;
    }
}