<?php
include_once  dirname(__FILE__). '/../bootstrap.php';

class UserTest extends PHPUnit_Framework_TestCase
{
    public function testIsAuthenticated()
    {
        $user = new User();
        $this->assertFalse($user->isAuthenticated());
    }

    public function testCheckPasswordValid()
    {
        $user = $this->login();
        $this->assertTrue($user->isAuthenticated());
    }

    /**
     * @return User
     */
    public function login()
    {
        $user = new User();
        $_POST['username'] = 'admin';
        $_POST['password'] = 'password';
        $user->signIn();
        return $user;
    }

    public function testCheckPasswordInValid()
    {
        $user = new User();
        $_POST['username'] = 'admin';
        $_POST['password']  = 'notvalidpassword';
        $user->signIn();
        $this->assertTrue($user->isAnonymous());
    }

    public function testCheckPasswordInValidWithoutPostData()
    {
        $user = new User();
        $user->signIn();
        $this->assertTrue($user->isAnonymous());
    }

    public function testSignOut()
    {
        $user = $this->login();
        $user->signOut();
        $this->assertFalse($user->isAuthenticated());
    }

    public function testGetCurrentUser()
    {
        $this->assertTrue(User::getCurrentUser() instanceof User);
    }


}