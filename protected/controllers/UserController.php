<?php
declare(strict_types=1);

namespace prime\controllers;


use prime\actions\DeleteAction;
use prime\components\Controller;
use prime\controllers\user\Account;
use prime\controllers\user\ConfirmEmail;
use prime\controllers\user\Impersonate;
use prime\controllers\user\Index;
use prime\controllers\user\UpdateEmail;
use prime\models\ar\User;

class UserController extends Controller
{
    public $layout = 'map-popover';

    public function actions()
    {
        return [
            'confirm-email' => ConfirmEmail::class,
            'update-email' => UpdateEmail::class,
            'index' => Index::class,
            'account' => Account::class,
            'impersonate' => Impersonate::class,
            'delete' => [
                'class' => DeleteAction::class,
                'query' => User::find()
            ]
        ];
    }

}