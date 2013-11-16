<?php

class Api extends ApiBase
{
    
    public function rules() {
        $rules = parent::rules();
        $newRules = array_merge($rules, array(
            array('id','default',
                 'value' => Utils::getRandStr(),
                 'setOnEmpty' => true, 'on' => 'insert'),
            array('updated', 'default',
                'value' => new CDbExpression('NOW()'),
                'setOnEmpty' => false, 'on' => 'update'),
            array('created,updated', 'default',
                'value' => new CDbExpression('NOW()'),
                'setOnEmpty' => false, 'on' => 'insert')
        ));
        
        return $newRules;
    }
    
    public function beforeDelete()
    {
        parent::beforeDelete();
        foreach($this->apiOperations as $apiOp){
            $apiOp->delete();
        }
        return true;
    }
    
    public function toArray()
    {
        $api = array(
            'id' => $this->id,
            'application_id' => $this->application_id,
            'path' => $this->path,
            'description' => $this->description,
            'created' => $this->created,
            'updated' => $this->updated,
        );
        
        return $api;
    }
    
    public function toJson()
    {   
        return CJSON::encode($this->toArray());
    }
    
    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return UserBase the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
}