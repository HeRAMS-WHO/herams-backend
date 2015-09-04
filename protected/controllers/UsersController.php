<?php

namespace app\controllers;

use app\components\Controller;
use app\models\User;

class UsersController extends Controller
{

    public function actionLogin(){

        if(!app()->user->isGuest) {
            return $this->goHome();
        }

        if(app()->request->isPost) {
            $user = new User(['scenario' => 'login']);
        } else {
            $user = new User(['scenario' => 'login']);
        }

        return $this->render('login', ['user' => $user]);
    }

}