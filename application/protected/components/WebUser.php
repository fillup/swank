<?php

class WebUser extends CWebUser {
    private $_model = null;
    
    public function getRole() {
        if($user = $this->getModel()){
            return $user->role;
        }
    }
 
    private function getModel(){
        if (!$this->isGuest && $this->_model === null){
            $this->_model = User::model()->findByPk($this->id, array('select' => 'role'));
        }
        return $this->_model;
    }
    
    /**
     * Override default method to simply check if user has role
     * 
     * @param string $role
     * @param array $params IGNORED
     * @param boolean $allowCaching IGNORED
     */
    public function checkAccess($role, $params=array(),$allowCaching=true)
    {
        if($this->getRole() == $role || $role == '*'){
            return true;
        }
        
        if($this->isGuest && $role == '@'){
            return true;
        }
        
        if(!$this->isGuest && $role == '?'){
            return true;
        }
        
        return false;
    }
}
