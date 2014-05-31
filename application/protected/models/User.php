<?php

class User extends UserBase
{
    const ROLE_USER = 'user';
    const ROLE_ADMIN = 'admin';
    const ROLE_DEFAULT = 'user';
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;
    const STATUS_BANNED = -1;
    
    public function rules() {
        $rules = parent::rules();
        $newRules = array_merge($rules, array(
            array('id','default',
                 'value' => Utils::getRandStr(),
                 'setOnEmpty' => true, 'on' => 'insert'),
            array('id, access_token, api_token','unsafe'),
            array('last_login', 'default',
                'value' => new CDbExpression('NOW()'),
                'setOnEmpty' => false, 'on' => 'update'),
            array('created,last_login', 'default',
                'value' => new CDbExpression('NOW()'),
                'setOnEmpty' => false, 'on' => 'insert')
        ));
        
        return $newRules;
    }
    
    public static function findOrCreate($email)
    {
        $user = User::model()->findByAttributes(array('email' => $email));
        if($user){
            return $user;
        } else {
            $user = new User();
            $user->email = $email;
            $user->name = $email;
            $user->role = self::ROLE_DEFAULT;
            $user->status = self::STATUS_ACTIVE;
            if($user->save()){
                return $user;
            } else {
                throw new Exception('Unable to create new user: '.print_r($user->getErrors(),true));
            }
        }
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