<?php

class Application extends ApplicationBase
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
                'setOnEmpty' => false, 'on' => 'insert'),
            array('name', 'match', 'allowEmpty' => false,
                'not' => false, 'pattern' => '/[a-zA-Z0-9 ]{2,}/'),
            array('base_path', 'match', 'allowEmpty' => false,
                'not' => false, 'pattern' => '/http[s]?:\/\/(.*){1,}/'),
            array('resource_path', 'match', 'allowEmpty' => false,
                'not' => false, 'pattern' => '/^\/[a-zA-Z0-9]{1,}.*/'),
        ));
        
        return $newRules;
    }
    
    public function beforeDelete()
    {
        parent::beforeDelete();
        foreach($this->apis as $api){
            $api->delete();
        }
        return true;
    }
    
    public function toArray()
    {
        $app = array(
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'base_path' => $this->base_path,
            'resource_path' => $this->resource_path,
            'api_version' => $this->api_version,
            'created' => $this->created,
            'updated' => $this->updated,
            'apis' => array(),
        );
        
        foreach($this->apis as $api){
            $app['apis'][] = $api->toArray();
        }
        
        return $app;
    }
    
    public function toJson()
    {   
        return CJSON::encode($this->toArray());
    }
    
    public function toSwagger()
    {
        $app = array(
            'apiVersion' => $this->api_version,
            'swaggerVersion' => '1.2',
            'basePath' => $this->base_path,
            'apis' => array(),
            'models' => array(),
        );
        
        if(!is_null($this->resource_path)){
            $app['resourcePath'] = $this->resource_path;
        }
        
        foreach($this->apis as $api){
            $app['apis'][] = $api->toSwagger();
        }
        
        return $app;
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