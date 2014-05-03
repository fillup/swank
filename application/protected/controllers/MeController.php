<?php

class MeController extends Controller
{
    public function filters()
    {
        return array(
            'accessControl',
        );
    }
    
    public function filterAccessControl($filterChain)
    {
        if(Yii::app()->user->isGuest){
            $this->redirect('/auth/login');
        } else {
            $filterChain->run();
        }
    }
    
    public function actionIndex()
    {
        $myApps = new CActiveDataProvider('Application', array(
            'criteria' => array(
                'condition' => '`user_id`=\''.Yii::app()->user->getId().'\'',
                'order' => '`name` DESC',
            ),
            'pagination' => array(
                'pageSize' => 2,
            ),
        ));
        $this->render('index', array(
            'myApps' => $myApps,
        ));
    }
}