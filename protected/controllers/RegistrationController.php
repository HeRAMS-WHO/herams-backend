<?php

namespace prime\controllers;

use dektrium\user\models\RegistrationForm;
use prime\traits\MessageGoHomeTrait;
use yii\web\NotFoundHttpException;

class RegistrationController extends \dektrium\user\controllers\RegistrationController
{
    use MessageGoHomeTrait;
}