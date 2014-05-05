<?php

class UiController extends Controller
{ 
    public function actionIndex($id=false)
    {
        $error = $isOwner = false;
        $appName = null;
        if(!$id){
            $error = "An Application ID was not provided";
        } else {
            $app = Application::model()->findByPk($id);
            if(!$app){
                $error = "Invalid Application ID provided";
            } else {
                $appName = $app->name;
                if($app->user_id == Yii::app()->user->getId()){
                    $isOwner = true;
                }
            }
        }
        if($appName){
            $this->pageTitle = $appName.' - API Playground | '.Yii::app()->name;
        }
        $swaggerSpecUrl = Yii::app()->createAbsoluteUrl('/api/application',array('id' => $id,'swagger' => true));
        $this->render('index',array(
            'swaggerSpecUrl' => $swaggerSpecUrl,
            'appName' => $appName,
            'isOwner' => $isOwner,
            'error' => $error,
            'appId' => $id,
        ));
    }
}