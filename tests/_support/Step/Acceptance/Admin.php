<?php
namespace Step\Acceptance;

use Page\Login;
use Codeception\Scenario;

class Admin extends User
{
    public function __construct(Scenario $scenario, Login $loginPage)
    {
        parent::__construct($scenario, $loginPage);
    }

    public function login(Login $loginPage, $user = ADMIN_USER_NAME, $password = ADMIN_USER_PASS)
    {
        return parent::login($loginPage, $user, $password);
    }
}
