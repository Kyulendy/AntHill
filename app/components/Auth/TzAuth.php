<?php


class TzAuth {

    private static $tiitzVersion = '0.3';
    
    private $salt = 'awdOsmA||//DOEWPopjk%[awd[@0}}{adwdakl';

    public function login(array $values) {
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
                $value = $this->encryptPwd($value);
            }
            $query .= $field.' = "'.$value.'"';

            $i++;
        }

        $data = TzSQL::getPDO()->prepare($query);
        $data->execute();
        $user = $data->fetchAll(PDO::FETCH_ASSOC);
        if(!empty($user)){
            $this->addUserSession($user[0]);
            return true;
        } else {
            return false;
        }
    }

    public function logout() {
       if(!$this->isUserLoggedIn()){
            return "__ERROR NO USER__";
        }
        $this->emptyUserSession();
    }

    public function isUserLoggedIn(){
        return(!empty($_SESSION['User']));
    }

    public function addSession(string $fieldName, mixed $value){
        $_SESSION['Data'][$fieldName] = $value;
    }

    private function addUserSession(array $data){
        foreach ($data as $field => $value) {
            $_SESSION['User'][$field] = $value;
        }
    }

    public function emptySessionData(){
        $_SESSION['Data'] = array();
    }

    private function emptyUserSession(){
        $_SESSION['User'] = array();
    }

    public function readUser($field = null){
        if(!$this->isUserLoggedIn()){
            return "__ERROR NO USER__";
        }
        if(is_null($field)){
            return $_SESSION['User'];
        } else {
            if(isset($_SESSION['User'][$field])){
                return $_SESSION['User'][$field];
            } else {
                return "__ERROR MISSING FIELD__";
            }
        }
    }

    public function readSession($field = null){
        if(is_null($field)){
            return $_SESSION['Data'];
        } else {
            if(isset($_SESSION['Data'][$field])){
                return $_SESSION['Data'][$field];
            } else {
                return "__ERROR MISSING FIELD__";
            }
        }
    }

    public function getSalt() {
        return $this->salt;
    }


    public function encryptPwd($pwd){
        $pwd = sha1(md5($pwd.$this->salt));
        return $pwd;
    }
}