<?php

class User
{
    public static $users = array(
        array(
            'login' => 'admin',
            'password' => 'password',
            //'rights' => array('edit')
        )
    );

    public function __construct()
    {
        session_start();
    }

    function isAuthenticated()
    {
        return isset($_SESSION['auth_status']);
    }

    function isAnonymous()
    {
        return !$this->isAuthenticated();
    }

    function signIn()
    {
        if (isset($_POST['username'])) {
            if (self::checkPassword($_POST['username'], $_POST['password'])) {
                $_SESSION['auth_status'] = true;
                return true;
            } else {
                return array('invalid password');
            }
        }
        return false;
    }

    function signOut()
    {
        session_destroy();
        $_SESSION = array();
    }

    function checkPassword($login, $password)
    {
        $stmt = DB::getInstance()->getConnection()->prepare('select count(*) from users where username = :username and password = :password');
        $stmt->bindValue('username', $login);
        $stmt->bindValue('password', $password);

        $stmt->execute();
        $all = $stmt->fetchAll();
        return $all[0][0] >= 1;
    }

    public static function getCurrentUser()
    {
        return new User();
    }

}