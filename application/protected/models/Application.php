<?php

class Application extends ApplicationBase
{

    public static $AUTHORIZATION_TYPES = array(
        'none','api_key',
    );
    
    public function rules() {
        $rules = parent::rules();
        $newRules = array_merge($rules, array(
            array('id','default',
                 'value' => Utils::getRandStr(),
                 'setOnEmpty' => true, 'on' => 'insert'),
            array('id','unsafe'),
            array('updated', 'default',
                'value' => new CDbExpression('NOW()'),
                'setOnEmpty' => false, 'on' => 'update'),
            array('created, updated', 'default',
                'value' => new CDbExpression('NOW()'),
                'setOnEmpty' => false, 'on' => 'insert'),
            array('name', 'match', 'allowEmpty' => false,
                'not' => false, 'pattern' => '/[a-zA-Z0-9 ]{2,}/'),
            array('base_path', 'match', 'allowEmpty' => false,
                'not' => false, 'pattern' => '/http[s]?:\/\/(.*){1,}/'),
            array('resource_path', 'match', 'allowEmpty' => false,
                'not' => false, 'pattern' => '/^\/[a-zA-Z0-9\{\}#]{1,}.*/'),
            array('visibility','in','range'=>array('public','unlisted'),
                'allowEmpty' => false, 'message' => 'Visibility is required. '
                . 'Valid options are: public, unlisted'),
            array('authorization_type','in','range'=>self::$AUTHORIZATION_TYPES,
                'allowEmpty' => false, 'message' => 'Authorization Type is required. Valid options are: '.implode(self::$AUTHORIZATION_TYPES)),
            array('authorization_config','isJson',
                'allowEmpty' => true, 'message' => 'Authorization Config does not appear to be valid JSON.'),
            array('authorization_config','default',
                'value' => null, 'setOnEmpty' => true),
        ));
        
        return $newRules;
    }

    /**
     * Ensure that value is proper json if not allowed to be empty
     * @param string $attribute the name of the attribute to be validated
     * @param array $params options specified in the validation rule
     */
    public function isJson($attribute,$params)
    {
        if($params['allowEmpty'] === false && (is_null($this->$attribute) || $this->$attribute == '' )){
            $this->addError($attribute,'first, '.$params['message']);
        }
        if(!is_null($this->$attribute) && $this->$attribute != ''){
            if(!is_array($this->$attribute)){
                $value = json_decode($this->$attribute,true);
                if(!is_array($value)){
                    $this->addError($attribute,'second, '.$params['message']);
                }
            }
        }
    }

    /**
     * Before saving an application, make sure authorization_config is a json encoded string
     */
    public function beforeSave()
    {
        parent::beforeSave();
        if(is_array($this->authorization_config)){
            $this->authorization_config = json_encode($this->authorization_config);
        }
        return true;
    }

    /**
     * Before deleting an application, go delete all of its APIs.
     * @return bool
     */
    public function beforeDelete()
    {
        parent::beforeDelete();
        foreach($this->apis as $api){
            $api->delete();
        }
        return true;
    }

    /**
     * Return application as an array
     * @return array
     */
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
            'visibility' => $this->visibility,
            'authorization_type' => $this->authorization_type,
            'apis' => array(),
        );

        if(!is_null($this->authorization_config)){
            $config = json_decode($this->authorization_config,true);
            if(is_array($config)){
                $app['authorization_config'] = $config;
            }
        }
        
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
            'resourcePath' => $this->resource_path,
        );
        
        if(!is_null($this->resource_path)){
            //$app['resourcePath'] = $this->resource_path;
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