<?php
declare(strict_types=1);

namespace prime\controllers;


use prime\actions\DeleteAction;
use prime\components\Controller;
use prime\controllers\user\Account;
use prime\controllers\user\ConfirmEmail;
use prime\controllers\user\Create;
use prime\controllers\user\Impersonate;
use prime\controllers\user\Index;
use prime\controllers\user\RequestAccount;
use prime\controllers\user\RequestReset;
use prime\controllers\user\ResetPassword;
use prime\controllers\user\Roles;
use prime\controllers\user\UpdateEmail;
use prime\controllers\user\UpdatePassword;
use prime\models\ar\User;
use SamIT\Yii2\Traits\ActionInjectionTrait;
use yii\helpers\ArrayHelper;

class UserController extends Controller
{
    use ActionInjectionTrait;
    public $layout = 'map-popover';

    public function actions()
    {
        return [
            'confirm-email' => ConfirmEmail::class,
            'update-email' => UpdateEmail::class,
            'index' => Index::class,
            'account' => Account::class,
            'impersonate' => Impersonate::class,
            'request-account' => RequestAccount::class,
            'request-reset' => RequestReset::class,
            'reset-password' => ResetPassword::class,
            'update-password' => UpdatePassword::class,
            'roles' => Roles::class,
            'create' => Create::class,
            'delete' => [
                'class' => DeleteAction::class,
                'query' => User::find()
            ]
        ];
    }

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
           'access' => [
               'rules' => [
                   [
                       'allow' => true,
                       'actions' => ['request-account', 'create', 'request-reset', 'reset-password']
                   ],
                   [
                       'allow' => true,
                       'roles' => ['@'],
                       'actions' => ['account']
                   ]
               ]
           ]
        ]);
    }


}