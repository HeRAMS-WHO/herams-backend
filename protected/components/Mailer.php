<?php

namespace prime\components;

use dektrium\user\models\Token;
use dektrium\user\models\User;

class Mailer extends \dektrium\user\Mailer
{
    /** @var string */
    public $viewPath = '@app/mail';

}