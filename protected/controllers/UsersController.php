<?php

namespace app\controllers;

use app\components\Controller;

class UsersController extends Controller
{

    public function actionLogin(){
        return $this->render('login');
    }

}