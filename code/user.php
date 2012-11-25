<?php
session_start();

$users = array(
    array(
        'login' => 'admin',
        'password' => 'password',
        //'rights' => array('edit')
    )
);

function isAuthenticated()
{
    return isset($_SESSION['auth_status']);
}

function isAnonymous()
{
    return !isAuthenticated();
}

function signIn()
{
    if (isset($_POST['username'])) {
        if (checkPassword($_POST['username'], $_POST['password'])) {
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

function hasPermission()
{

}