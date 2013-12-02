<?php

class ApiParameter extends ApiParameterBase
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