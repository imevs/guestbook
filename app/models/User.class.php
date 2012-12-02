<?php
session_start();

/**
 */
class User
{

    public $users = array(
        array(
            'login' => 'admin',
            'password' => 'password',
            //'rights' => array('edit')
        )
    );

    public function __construct()
    {

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
    }

    function checkPassword($login, $password)
    {
        global $users;
        $arr = array('login' => $login, 'password' => $password);
        return array_search($arr, $users) !== false;
    }

    public static function getCurrentUser()
    {
        return new User();
    }

}