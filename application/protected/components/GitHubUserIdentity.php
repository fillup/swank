<?php

use Guzzle\Http\StaticClient as Guzzle;

class GitHubUserIdentity extends CUserIdentity
{

    protected $config;
    protected $access_token;
    protected $code;
    public $id;
    public $email;
    public $name;

    public function __construct($code = false)
    {
        $this->config = Yii::app()->params['github'];
        $this->code = $code;
    }

    public function getLoginUrl($return = false)
    {
        if ($return) {
            Yii::app()->session['login_redirect'] = $return;
        } else {
            unset(Yii::app()->session['login_redirect']);
        }

        $state = Utils::getRandStr();
        Yii::app()->session['login_state'] = $state;

        $url = $this->config['auth_url'];
        $url .= '?client_id=' . $this->config['client_id'];
        $url .= '&scope=' . $this->config['scope'];
        $url .= '&state=' . $state;

        return $url;
    }

    public function authenticate()
    {
        if ($this->code) {
            $response = Guzzle::post($this->config['token_url'], array(
                        'headers' => array('Accept' => 'application/json'),
                        'body' => array(
                            'client_id' => $this->config['client_id'],
                            'client_secret' => $this->config['client_secret'],
                            'state' => Yii::app()->session['login_state'],
                            'code' => $this->code,
                        ),
            ));

            if ($response->isSuccessful()) {
                $results = $response->json();
                if (is_array($results) && isset($results['access_token'])) {
                    $this->access_token = $results['access_token'];
                    $userInfo = $this->getUserInformation();
                    if ($userInfo) {
                        try {
                            $user = User::findOrCreate($userInfo['emails'][0]['email']);
                            $user->name = $userInfo['name'];
                            $user->access_token = $this->access_token;
                            if ($user->save()) {
                                $this->loadIdentity($user);
                            } else {
                                Yii::app()->user->setFlash('danger', "There was a problem creating your account, maybe try again in a moment? (".__LINE__.")");
                                $this->errorCode = self::ERROR_UNKNOWN_IDENTITY;
                            }
                        } catch (Exception $e) {
                            Yii::app()->user->setFlash('danger', "There was a problem creating your account, maybe try again in a moment? (".__LINE__.")");
                            $this->errorCode = self::ERROR_UNKNOWN_IDENTITY;
                        }
                    } else {
                        $this->errorCode = self::ERROR_UNKNOWN_IDENTITY;
                    }
                } else {
                    Yii::app()->user->setFlash('danger', "There was a problem with the response from GitHub, maybe try again in a moment? (".__LINE__.")");
                    $this->errorCode = self::ERROR_UNKNOWN_IDENTITY;
                }
            } else {
                Yii::app()->user->setFlash('danger', "There was a problem with the response from GitHub, maybe try again in a moment? (".__LINE__.")");
                $this->errorCode = self::ERROR_UNKNOWN_IDENTITY;
            }
        } else {
            Yii::app()->user->setFlash('danger', "There was a problem with the response from GitHub, maybe try again in a moment? (".__LINE__.")");
            $this->errorCode = self::ERROR_UNKNOWN_IDENTITY;
        }

        return !$this->errorCode;
    }

    public function getUserInformation()
    {
        $url = $this->config['user_url'] . '?access_token=' . $this->access_token;
        $response = Guzzle::get($url, array(
                    'headers' => array(
                        'Accept' => 'application/json',
                    ),
        ));
        if ($response->isSuccessful()) {
            $userInfo = $response->json();
            if (is_array($userInfo)) {
                $url2 = $this->config['user_url'] . '/emails?access_token=' . $this->access_token;
                $response2 = Guzzle::get($url2, array(
                            'headers' => array(
                                'Accept' => 'application/json',
                            ),
                ));
                if($response2->isSuccessful()){
                    $emailInfo = $response2->json();
                    if(is_array($emailInfo)){
                        $userInfo['emails'] = $emailInfo;
                        return $userInfo;
                    } else {
                        Yii::app()->user->setFlash('danger','Unable to retreive email address from GitHub. Did you disable access when requested? ('.__LINE__.')');
                        return false;
                    }
                } else {
                    Yii::app()->user->setFlash('danger','Unable to retreive email address from GitHub. Did you disable access when requested? ('.__LINE__.')');
                    return false;
                }
            } else {
                Yii::app()->user->setFlash('danger', "There was a problem with the response from GitHub, maybe try again in a moment? (".__LINE__.")");
                return false;
            }
        } else {
            Yii::app()->user->setFlash('danger', "There was a problem with the response from GitHub, maybe try again in a moment? (".__LINE__.")");
            return false;
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function loadIdentity(UserBase $user)
    {
        $this->setState('user', $user);
        $this->setState('role', $user->role);
        $this->id = $user->id;
        $this->username = $user->email;
        $this->name = $user->name;
        $this->errorCode = self::ERROR_NONE;
    }

}
