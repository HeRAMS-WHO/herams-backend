<?php

namespace prime\widgets;

use yii\base\Widget;

class User extends Widget
{
    /**
     * @var \prime\models\ar\User
     */
    public $user;

    public function init()
    {
        parent::init();
        if(!$this->user instanceof \prime\models\ar\User) {
            throw new \Exception("User must be instance of " . \prime\models\ar\User::class);
        }
    }

    public function run()
    {
        parent::run();
        return $this->render('user', ['user' => $this->user]);
    }


}