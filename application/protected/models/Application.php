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