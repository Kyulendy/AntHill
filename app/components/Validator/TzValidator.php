<?php

/**
 * TiiTz Generic pattern validator
 *
 * @author Hodor
 */

namespace Components\Validator;

class TzValidator {
    
    const BAD_EMAIL             = "ERR_EMAIL";
    const BAD_IP                = "ERR_IP";
    const BAD_INT               = "ERR_INT";
    const BAD_FLOAT             = "ERR_FLOAT";
    const BAD_STRING            = "ERR_STRING";
    const BAD_LENGTH            = "ERR_LENGTH";
    const BAD_LENGTH_PATTERN    = "ERR_LENGTH_PATTERN";
    const BAD_URL               = "ERR_URL";
    const BAD_PATTERN           = "ERR_PATTERN";
    const BAD_TYPE_PATTERN      = "ERR_TYPE_PATTERN";
    const BAD_FLAG              = "ERR_FLAG";
    
    private static $errors = array();

    private static function addError($elem, $err){
        self::$errors[$elem]= $err;
    }
    
    public static  function getErrors(){
        return(self::$errors);
    }
    
    public static function checkTypes($elem, $type){
        
        if($type == "EMAIL"){
            if(self::isMail($elem) != TRUE){
                self::addError($elem, self::BAD_EMAIL);
                return false;
            }
            return(true);
        }
        if($type == "IP"){
            if(self::isIp($elem) != TRUE){
                self::addError($elem, self::BAD_IP);
                return false;
            }
            return(TRUE);
        }
        if($type == "STRING"){
            if(self::isString($elem) != TRUE){
                self::addError($elem, self::BAD_STRING);
                return false;
            }
            return(TRUE);
        }
        if($type == "URL"){
            if(self::isUrl($elem) != TRUE){
                self::addError($elem, self::BAD_URL);
                return false;
            }
            return(TRUE);
        }
        if($type == "INT"){

            if(self::isInt($elem) != TRUE){
                self::addError($elem, self::BAD_INT);
                return false;
            }
            return(TRUE);
        }
        if($type == "FLOAT"){
            if(self::isFloat($elem) != TRUE){
                self::addError($elem, self::BAD_FLOAT);
                return false;
            }
            return(TRUE);
        }
        self::addError($elem, self::BAD_TYPE_PATTERN);
        return(FALSE);
    }
    
    
    /* Check the length of a string for the checkMulti function*/
    public static function checkLength($elem, $constraint){
        $nbChar = "";
        $toCompare = str_split(str_replace(" ", "",$constraint));
        if(($toCompare[0] == "<" && $toCompare[1] == "=") || ($toCompare[0] == ">" && $toCompare[1] == "=") || ($toCompare[0] == "=" && $toCompare[1] == "=") || ($toCompare[0] == "!" && $toCompare[1] == "=")){
            foreach($toCompare as $k=>$v){
                if($k != 0 && $k != 1){
                    if(ctype_digit($v)){
                        $nbChar .= $v;
                    }
                    else{
                        self::addError($elem, self::BAD_LENGTH_PATTERN);
                        return(TRUE);
                    }
                }
            }
            if($toCompare[0] == "<" && $toCompare[1] == "="){
                if(strlen($elem) <= $nbChar){
                    return("");
                }
                else{
                    self::addError($elem, self::BAD_LENGTH);
                    return(TRUE);
                }
            }
            if($toCompare[0] == ">" && $toCompare[1] == "="){
                if(strlen($elem) >= $nbChar){
                    return("");
                }
                else{
                    self::addError($elem, self::BAD_LENGTH);
                    return(TRUE);
                }
            }
            if($toCompare[0] == "!" && $toCompare[1] == "="){
                if(strlen($elem) != $nbChar){
                    return("");
                }
                else{
                    self::addError($elem, self::BAD_LENGTH);
                    return(TRUE);
                }
            }
            if($toCompare[0] == "=" && $toCompare[1] == "="){
                if(strlen($elem) == $nbChar){
                    return("");
                }
                else{
                    self::addError($elem, self::BAD_LENGTH);
                    return(TRUE);
                }   
            }
            self::addError($elem, self::BAD_LENGTH_PATTERN);
            return(TRUE);
        }
        if($toCompare[0] == "<" || $toCompare[0] == ">"){
            foreach($toCompare as $k=>$v){
                if($k != 0){
                    if(ctype_digit($v)){
                        $nbChar .= $v;
                    }
                    else{
                        self::addError($elem, self::BAD_LENGTH_PATTERN);
                        return(TRUE);
                    }
                }
            }
            if($toCompare[0] == "<"){
                if(strlen($elem) < $nbChar){
                    return("");
                }
                else{
                    self::addError($elem, self::BAD_LENGTH);
                    return(TRUE);
                }
            }
            if($toCompare[0] == ">" && $nbChar){
                if(strlen($elem) > $toCompare[1]){
                    return(TRUE);
                }
                else{
                    self::addError($elem, self::BAD_LENGTH);
                    return(TRUE);
                }
            }
        }
        self::addError($elem, self::BAD_LENGTH_PATTERN);
        return(TRUE);
    }
    
    /*  
     *  VALIDATION METHODS  
    */

    /*VALIDATES VALUE AS EMAIL ADDRESS: returns the message linked to the BAD_EMAIL CONST if email is not valid, else returns an empty string*/
    public static function isMail($mail){
        if(!filter_var($mail, FILTER_VALIDATE_EMAIL)){
            return(FALSE);
        }
        return(TRUE);
    }
    
    /*VALIDATES VALUE AS URL: returns the message linked to the BAD_URL CONST if url is not valid, else returns an empty string*/
    public static function isUrl($url){
        if(!filter_var($url, FILTER_VALIDATE_URL)){
            return(FALSE);
        }
        return(TRUE);
    }
    
    /*VALIDATES VALUE AS IP ADDRESS: returns the message linked to the BAD_IP CONST if number is not an integer, else returns an empty string*/
    public static function isIp($ip){
        if(!filter_var($ip, FILTER_VALIDATE_IP)){
            return(FALSE);
        }
        return(TRUE);
    }
    
    /*VALIDATES VALUE AS STRING: returns the message linked to the BAD_STRING CONST if str is not valid, else returns an empty string*/
    public static function isString($str){
        if(!is_string($str)){
            return(FALSE);
        }
        return(TRUE);
    }
    
    /*VALIDATES VALUE AS INTEGER: returns the message linked to the BAD_INT CONST if number is not an integer, else returns an empty string*/
    public static function isInt($number){
        if(!filter_var($number, FILTER_VALIDATE_INT)){
            return(FALSE);
        }
        return(TRUE);
    }
    
    /*VALIDATES VALUE AS FLOAT: returns the message linked to the BAD_FLOAT CONST if numberFloat is not a float, else returns an empty string*/
    public static function isFloat($numberFloat){
        if(!filter_var($numberFloat, FILTER_VALIDATE_FLOAT)){
            return(FALSE);
        }
        return(TRUE);
    }
    
    /*VALIDATES VALUE AGAINST PATTERN: returns the message BAD_PATTERN CONST if toCheck does not match pattern, else returns an empty string*/
    public static function isStringExp($toCheck, $pattern){
        if(!filter_var($toCheck, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>$pattern)))){
            return(FALSE);
        }
        return(TRUE);
    }
    
    /* 
     * CLEANING METHODS 
     */
    
    /*CLEANS AN EMAIL: Remove all characters except letters, digits and !#$%&'*+-/=?^_`{|}~@.[]*/
    public static function cleanMail($mail){
        return(filter_var($mail, FILTER_SANITIZE_EMAIL));
    }
    
    /*CLEANS AN URL: URL-encode string, optionally strip or encode special characters*/
    public static function cleanUrl($url){
        return(filter_var($url, FILTER_SANITIZE_URL));
    }
    
    /*CLEANS A NUMBER : delete all chars, except numbers, +- */
    public static function cleanInt($number){
        return(filter_var($number, FILTER_SANITIZE_NUMBER_INT));
    }
    
    /*CLEANS A FLOAT NUMBER : Remove all characters except digits, +- and optionally .,eE*/
    public static function cleanFloat($numberFloat){
        return(filter_var($numberFloat, FILTER_SANITIZE_NUMBER_FLOAT));
    }
    
    /* 
     * PARSER FOR MULTI CONSTRAINTS CHECK 
     */
    
    public static function checkMulti($elems){
        if($elems != null){
            foreach ($elems as $req){
                if(array_key_exists("ELEM", $req)){
                    
                    if(array_key_exists("LENGTH", $req)){
                        self::checkLength($req["ELEM"], $req["LENGTH"]);
                    }
                    if(array_key_exists("TYPE", $req)){
                        self::checkTypes($req["ELEM"], $req["TYPE"]);
                    }
                }
            }
        }
    }
}