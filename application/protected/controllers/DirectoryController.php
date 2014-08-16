<?php

class DirectoryController extends Controller
{
    public function actionIndex()
    {
        $req = Yii::app()->request;
        $query = $req->getParam('query',false);

        $criteria = new CDbCriteria();
        $criteria->order = '`name` DESC';
        $criteria->compare('visibility','public',false,'AND');
        if($query){
            $criteria->compare('name',$query,true,'OR',true);
            $criteria->compare('description',$query,true,'OR',true);
            $criteria->compare('base_path',$query,true,'OR',true);
        }

        $apps = new CActiveDataProvider('Application', array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 20,
            ),
        ));
        $this->render('index', array(
            'apps' => $apps,
            'query' => $query,
        ));
    }
}