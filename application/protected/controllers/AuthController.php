<?php

class AuthController extends Controller
{
    public function actionLogin()
    {
        $identity = new GitHubUserIdentity();
        $this->redirect($identity->getLoginUrl(Yii::app()->user->returnUrl));
    }
    
    public function actionLogout()
    {
        Yii::app()->user->clearStates();
        Yii::app()->user->logout(true);
        $this->redirect('/');
    }
    
    public function actionReturn()
    {
        $req = Yii::app()->request;
        $code = $req->getParam('code',false);
        $state = $req->getParam('state',false);
        
        if(!isset(Yii::app()->session['login_state']) || $state != Yii::app()->session['login_state'] || !$code){
            Yii::app()->user->setFlash('danger','Invalid login attempt');
            $this->redirect('/');
        } else {
            $identity = new GitHubUserIdentity($code);
            if($identity->authenticate()){
                Yii::app()->user->login($identity);
                $url = isset(Yii::app()->session['login_redirect']) ? 
                        Yii::app()->session['login_redirect'] : 
                       isset(Yii::app()->user->returnUrl) ? 
                        Yii::app()->user->returnUrl : '/';
                unset(Yii::app()->session['login_redirect']);
                $this->redirect($url);
            } else {
                $this->redirect('/');
            }
        }
    }
    
}
