<?php

class GitHubUserIdentity extends CUserIdentity
{
    protected $auth;
    protected $config;
    public $id;
    public $username;
    public $name;
    
    public function __construct() 
    {
        $this->config = Yii::app()->params['github'];
    }
    
    public function authenticate()
    {
        if(!$this->auth->isAuthenticated()){
            $this->errorCode = self::ERROR_UNKNOWN_IDENTITY;
        } else {
            $attrs = $this->auth->getAttributes();
            $email = $attrs[$this->config['map']['emailField']][$this->config['map']['emailFieldElement']];
            if($email){
                $user = User::model()->findByAttributes(array('email' => $email));
                if($user){
                    $accessGroups = $this->extractAccessGroups($attrs['insiteAccessGroups']);
                    $this->loadIdentity($user,$accessGroups);
                } else {
                    $user = new User();
                    $user->email = $attrs[$this->config['map']['emailField']][$this->config['map']['emailFieldElement']];
                    $user->first_name = $attrs[$this->config['map']['firstNameField']][$this->config['map']['firstNameFieldElement']];
                    $user->last_name = $attrs[$this->config['map']['lastNameField']][$this->config['map']['lastNameFieldElement']];
                    $user->display_name = $user->first_name.' '.$user->last_name;//$attrs[$this->config['map']['displayNameField']][$this->config['map']['displayNameFieldElement']];
                    $user->role = User::ROLE_USER;
                    $user->status = 1;
                    $user->save();
                    $accessGroups = $this->extractAccessGroups($attrs['insiteAccessGroups']);
                    $this->loadIdentity($user,$accessGroups);
                }
            }
        }
        
        return !$this->errorCode;
    }
    
    public function getLoginUrl($return=null)
    {
        return $this->auth->getLoginURL($return);
    }
    
    public function getLogoutUrl($return=null)
    {
        return $this->auth->getLogoutURL($return);
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function extractAccessGroups($accessGroupList)
    {
        $accessGroups = array();
        foreach($accessGroupList as $group){
            $parts = explode(',', $group);
            $parts = explode('=',$parts[0]);
            $accessGroups[] = strtoupper($parts[1]);
        }
        return $accessGroups;
    }
    
    public function loadIdentity(UserBase $user,$accessGroups = array())
    {
        $this->setState('user', $user);
        $this->setState('role',$user->role);
        $this->setState('accessGroups',$accessGroups);
        $this->id = $user->user_id;
        $this->username = $user->email;
        $this->name = $user->first_name.' '.$user->last_name;
        $this->errorCode=self::ERROR_NONE;
    }
}
