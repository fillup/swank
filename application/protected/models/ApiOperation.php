<?php

class ApiOperation extends ApiOperationBase
{
    
    public $validTypes = array(
        'void','array','integer','number','string','boolean','date','date-time'
    );
    
    public function rules() {
        $rules = parent::rules();
        $newRules = array_merge($rules, array(
            array('id','default',
                 'value' => Utils::getRandStr(),
                 'setOnEmpty' => true, 'on' => 'insert'),
            array('id','unsafe'),
            array('api_id','match','allowEmpty' => false, 'not' => false,
                'pattern' => '/[a-zA-Z0-9\-]{32}/', 'message' => 'Api ID required.'),
            array('method','in','range' => array('GET','POST','PUT','DELETE','PATCH'),
                'allowEmpty' => false, 'message' => 'Method must be a valid HTTP method: GET, POST, PUT, DELETE, PATCH.'),
            array('type','in','range' => $this->validTypes,
                'allowEmpty' => false, 'message' => 'Type must be a valid swagger type: '.implode(', ',$this->validTypes)),
            array('updated', 'default',
                'value' => new CDbExpression('NOW()'),
                'setOnEmpty' => false, 'on' => 'update'),
            array('created,updated', 'default',
                'value' => new CDbExpression('NOW()'),
                'setOnEmpty' => false, 'on' => 'insert')
        ));
        
        return $newRules;
    }
    
    public function toArray()
    {
        $data = array(
            'id' => $this->id,
            'api_id' => $this->api_id,
            'method' => $this->method,
            'nickname' => $this->nickname,
            'type' => $this->type,
            'summary' => $this->summary,
            'notes' => $this->notes,
            'created' => $this->created,
            'updated' => $this->updated,
            'parameters' => array(),
            'errorResponses' => array(),
        );
        
        foreach($this->apiParameters as $param){
            $data['parameters'][] = $param->toArray();
        }
        
        foreach($this->apiResponses as $resp){
            $data['errorResponses'][] = $resp->toArray();
        }
        
        return $data;
    }
    
    public function toJson()
    {
        return CJSON::encode($this->toArray());
    }
    
    public function toSwagger()
    {
        $op = array(
            'method' => strtoupper($this->method),
            'nickname' => $this->nickname,
            'type' => $this->type,
            'parameters' => array(),
            'summary' => $this->summary,
            'notes' => $this->notes,
            'errorResponses' => array(),
        );
        
        foreach($this->apiParameters as $param){
            $op['parameters'][] = $param->toSwagger();
        }
        
        foreach($this->apiResponses as $resp){
            $op['errorResponses'][] = $resp->toSwagger();
        }
        
        return $op;
    }
    
    public function beforeDelete()
    {
        parent::beforeDelete();
        foreach($this->apiParameters as $param){
            $param->delete();
        }
        foreach($this->apiResponses as $resp){
            $resp->delete();
        }
        return true;
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