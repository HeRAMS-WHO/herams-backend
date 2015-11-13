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
    }

    public function run()
    {
        parent::run();
        return $this->render('user', ['widget' => $this]);
    }


}