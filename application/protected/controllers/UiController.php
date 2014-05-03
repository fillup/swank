<?php

class UiController extends Controller
{ 
    public function actionIndex($id=false)
    {
        $this->layout = false;
        $swaggerSpecUrl = Yii::app()->createAbsoluteUrl('/api/application',array('id' => $id,'swagger' => true));
        $this->render('index',array(
            'swaggerSpecUrl' => $swaggerSpecUrl,
        ));
    }
}