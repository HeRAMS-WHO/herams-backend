<?php
namespace Step\Acceptance;

class AdminTester extends UserTester
{
    public function login($user = ADMIN_USER_NAME, $password = ADMIN_USER_PASS)
    {
        $I = $this;
        parent::login($user, $password);
        $I->seeInSource('Configuration', '.navbar');
    }

}