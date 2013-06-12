<?php


class TzAuth {
    
    private static $salt = '';

    public static function init($salt){
        self::$salt = $salt;
    }
    public static function login(array $values) {
        if(is_null(TzSQL::getPDO())){
            return false;
        }

        $query = 'SELECT * FROM users WHERE ';
        $i = 0;
        foreach ($values as $field => $value) {
            if($i != 0){
                $query .= ' AND ';
            }
            if($field == 'password'){
                $value = self::encryptPwd($value);
            }
            $query .= $field.' = "'.$value.'"';

            $i++;
        }

        $data = TzSQL::getPDO()->prepare($query);
        $data->execute();
        $user = $data->fetchAll(PDO::FETCH_ASSOC);
        if(!empty($user)){
            self::addUserSession($user[0]);
            return true;
        } else {
            return false;
        }
    }

    public static function logout() {
       if(!self::isUserLoggedIn()){
            return false;
        }
        self::emptyUserSession();
        return true;
    }

    public static function isUserLoggedIn(){
        return(!empty($_SESSION['User']));
    }

    public static function addSession($data, $value = null){
        if(is_null($value)){
            foreach ($data as $field => $value) {
                $_SESSION['Data'][$field] = $value;
            } 
        } elseif(is_string($data) && !is_null($value)) {
            $_SESSION['Data'][$fieldName] = $value;
        }
    }

    private static function addUserSession($data, $value = null){
        if(is_null($value)){
            foreach ($data as $field => $value) {
                $_SESSION['User'][$field] = $value;
            } 
        } else {
            $_SESSION['User'][$fieldName] = $value;
        }
    }

    public static function emptySessionData(){
        $_SESSION['Data'] = array();
    }

    private static function emptyUserSession(){
        $_SESSION['User'] = array();
    }

    public static function readUser($field = null){
        if(!self::isUserLoggedIn()){
            return false;
        }
        if(is_null($field)){
            return $_SESSION['User'];
        } else {
            if(isset($_SESSION['User'][$field])){
                return $_SESSION['User'][$field];
            } else {
                return false;
            }
        }
    }

    public static function readSession($field = null){
        if(is_null($field)){
            return $_SESSION['Data'];
        } else {
            if(isset($_SESSION['Data'][$field])){
                return $_SESSION['Data'][$field];
            } else {
                return false;
            }
        }
    }

    public static function getSalt() {
        return self::$salt;
    }


    public static function encryptPwd($pwd){
        $pwd = sha1(md5($pwd.self::$salt));
        return $pwd;
    }
}