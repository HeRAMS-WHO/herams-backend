<?php

namespace prime\controllers;

use prime\traits\MessageGoHomeTrait;

class RegistrationController extends \dektrium\user\controllers\RegistrationController
{
    use MessageGoHomeTrait;
}