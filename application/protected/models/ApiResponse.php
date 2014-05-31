<?php

class ApiResponse extends ApiResponseBase
{
    public $primitiveDataTypes = array(
        'integer','number','string','boolean','date','date-time'
    );

    public function rules() {
        $rules = parent::rules();
        $newRules = array_merge($rules, array(
            array('id','default',
                 'value' => Utils::getRandStr(),
                 'setOnEmpty' => true, 'on' => 'insert'),
            array('id','unsafe'),
            array('operation_id','match','allowEmpty' => false, 'not' => false,
                'pattern' => '/[a-zA-Z0-9\-]{32}/', 'message' => 'Operation ID required.'),
            array('code','match','allowEmpty' => false, 'not' => false,
                'pattern' => '/[0-9]{3}/', 'message' => 'A valid HTTP Status code is required.'),
            array('responseModel','in','range' => $this->primitiveDataTypes,
                'allowEmpty' => false, 'message' => 'Response type must be one of: '.implode(', ',$this->primitiveDataTypes)),
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
        return array(
            'id' => $this->id,
            'operation_id' => $this->operation_id,
            'code' => $this->code,
            'message' => $this->message,
            'responseModel' => $this->responseModel,
            'created' => $this->created,
            'updated' => $this->updated,
        );
    }

    public function toJson()
    {
        return json_encode($this->toArray());
    }
    
    public function toSwagger()
    {
        return array(
            'code' => $this->code,
            'message' => $this->message,
            'responseModel' => $this->responseModel,
        );
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