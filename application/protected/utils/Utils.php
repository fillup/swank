<?php

class Utils {    
    
    /**
     * Get random string of specified length. It is based on php function uniqid()
     * @param int $length [=32] the length of random string. Should be a positive integer.
     * @param string $salt salt string. default ''. It's quite safe to use default value,
     * as the latest function return will be stored and be part of the eventual salt, which means, empty $salt doesn't result in empty salt.
     * @throws InvalidArgumentException
     * @return string
     */
    public static function getRandStr($length = 32, $salt = '') {
        static $internal_salt = 'uaypiq2joi2j4fio2j24fjw5egtsrhsg5a4f;8kjcoiyad'; // this will be part of next salt.
        if(defined('APPLICATION_ENV') && APPLICATION_ENV == 'testing'){
            $str = 'test-';
        } elseif(defined('APPLICATION_ENV') && APPLICATION_ENV == 'development'){
           $str = 'dev-'; 
        } else {
            $str = '';
        }
        
        if ($length <= 0 || !is_numeric($length)) {
            throw new InvalidArgumentException('Parameter 1 should be a positive integer.');
        }

Rand_Gen_Loop:

        $internal_salt .= mt_rand(1, 10000000);
        $str .= $internal_salt = md5(uniqid($salt . $internal_salt, true)); // the string will replace $internal_salt.
        
        if(strlen($str) == $length){
            return $str;
        } elseif(strlen($str) > $length){
            return substr($str, 0, $length);
        } else {
            GOTO Rand_Gen_Loop;
        }
        
    }
    
    /*
     * @param string that matches a date and time.
     * @return string of that date and time formatted according to the $friendlyDateFormat constant in config/main.php
     */
    public static function getFriendlyDate($timeStr) {
        $timeStamp = strtotime($timeStr);

        $friendlyDateFormat = '%d-%b-%Y %H:%M:%S';
        if (isset(Yii::app()->params['friendlyDateFormat'])) {
            $friendlyDateFormat = Yii::app()->params['friendlyDateFormat'];
        }   
        return date($friendlyDateFormat, $timeStamp);
    }
    
    /*
     * @param string that matches a date and time.
     * @return string of that date and time formatted according to the $shortDateFormat constant in config/main.php
     */
    public static function getShortDate($timeStr) {
        $timeStamp = strtotime($timeStr);
                  
        $shortDateFormat = '%d-%b-%Y';
        if (isset(Yii::app()->params['shortDateFormat'])) {
            $shortDateFormat = Yii::app()->params['shortDateFormat'];
        }
        return date($shortDateFormat, $timeStamp);
    }
    
    /**
     * When model validation errors occur, you can call $model->getErrors()
     * The array returned is not friendly for display. This static method
     * will pull out the error messages and create an unordered list of 
     * class validation-errors and return the HTML.
     * 
     * @param array $errors
     * @return string
     */
    public static function modelErrorsAsHtml($errors)
    {
        $response = "<ul class='validation-errors'>";
        if(is_array($errors) && count($errors) >0){
            foreach($errors as $field){
                if(is_array($field)){
                    foreach($field as $error){
                        $response .= '<li>'.$error.'</li>';
                    }
                }
            }
        } else {
            $response .= '<li>No validation errors found, perhaps something else went wrong?</li>';
        }
        
        $response .= '</ul>';
        return $response;
    }
    
    /**
     * When model validation errors occur, you can call $model->getErrors()
     * The array returned is not friendly for display. This static method
     * will pull out the error messages and create a flat array of just
     * the messages.
     * 
     * @param array $errors
     * @return array
     */
    public static function modelErrorsAsArray($errors)
    {
        $response = array();
        if(is_array($errors) && count($errors) > 0){
            foreach($errors as $field){
                if(is_array($field)){
                    foreach($field as $error){
                        $response[] = $error;
                    }
                }
            }
        } else {
            $response[] = $errors;
        }
        
        return $response;
    }
}