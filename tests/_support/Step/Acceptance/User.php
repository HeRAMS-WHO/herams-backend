<?php

namespace Step\Acceptance;

use Page\Login;
use Codeception\Scenario;

class User extends Guest
{
    /**
     * Define custom actions here
     */

    public function login(Login $loginPage, $user = USER_NAME, $password = USER_PASS)
    {
            $loginPage->login($user, $password, false);
    }

    public function __construct(Scenario $scenario, Login $loginPage)
    {
        parent::__construct($scenario);
        $this->login($loginPage);
    }
}
