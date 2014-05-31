<?php

class ApiParameter extends ApiParameterBase
{
    
    public $primitiveDataTypes = array(
        'integer','number','string','boolean','date','date-time'
    );
    
    public $validParamTypes = array(
        'path' => array(
            'integer','number','string','boolean','date','date-time'
        ),
        'query' => array(
            'integer','number','string','boolean','date','date-time'
        ),
        //'body',
        'header' => array(
            'integer','number','string','boolean','date','date-time'
        ),
        'form' => array(
            'integer','number','string','boolean','date','date-time'
        ),
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
            array('paramType','in','range'=>array_keys($this->validParamTypes),
                'allowEmpty' => false, 'message' => 'Parameter type must be one of: '.implode(', ',array_keys($this->validParamTypes))),
            array('dataType','in','range'=>$this->primitiveDataTypes,
                'allowEmpty' => false, 'message' => 'Data type must be one of: '.implode(', ',$this->primitiveDataTypes)),
            array('format','default','value' => null, 'setOnEmpty' => true),
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
            'operation_id' => $this->operation_id,
            'paramType' => $this->paramType,
            'name' => $this->name,
            'description' => $this->description,
            'dataType' => $this->dataType,
            'format' => $this->format,
            'required' => $this->required,
            'minimum' => $this->minimum,
            'maximum' => $this->maximum,
            'enum' => $this->enum,
            'created' => $this->created,
            'updated' => $this->updated,
        );
        
        return $data;
    }
    
    public function toJson()
    {
        return CJSON::encode($this->toArray());
    }
    
    public function toSwagger()
    {
        $param = array(
            'paramType' => $this->paramType,
            'name' => $this->name,
            'description' => $this->description,
            'dataType' => $this->dataType,
            //'format' => $this->format,
            'required' => $this->required,
        );
        
        if(!is_null($this->minimum)){
            $param['minimum'] = $this->minimum;
        }
        if(!is_null($this->maximum)){
            $param['maximum'] = $this->maximum;
        }
        if(!is_null($this->minimum)){
            $param['minimum'] = $this->minimum;
        }
        if(!is_null($this->enum)){
            $param['enum'] = $this->enum;
        }
        
        return $param;
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