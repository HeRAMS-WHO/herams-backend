<?php

declare(strict_types=1);

namespace prime\controllers;

use herams\common\domain\user\User;
use prime\actions\DeleteAction;
use prime\components\Controller;
use prime\controllers\user\AcceptInvitation;
use prime\controllers\user\AccessRequests;
use prime\controllers\user\ConfirmEmail;
use prime\controllers\user\ConfirmInvitation;
use prime\controllers\user\Create;
use prime\controllers\user\Email;
use prime\controllers\user\Favorites;
use prime\controllers\user\GlobalUserRoles;
use prime\controllers\user\Impersonate;
use prime\controllers\user\Index;
use prime\controllers\user\Material;
use prime\controllers\user\Notifications;
use prime\controllers\user\Password;
use prime\controllers\user\Profile;
use prime\controllers\user\RequestReset;
use prime\controllers\user\ResetPassword;
use prime\controllers\user\Roles;
use SamIT\Yii2\UrlSigner\HmacFilter;
use yii\helpers\ArrayHelper;

class UserController extends Controller
{
    public $layout = self::LAYOUT_ADMIN_TABS;

    public function actions(): array
    {
        return [
            'accept-invitation' => AcceptInvitation::class,
            'access-requests' => AccessRequests::class,
            'confirm-email' => ConfirmEmail::class,
            'confirm-invitation' => ConfirmInvitation::class,
            'email' => Email::class,
            'global-user-roles' => GlobalUserRoles::class,
            'index' => Index::class,
            'material' => Material::class,
            'profile' => Profile::class,
            'impersonate' => Impersonate::class,
            'favorites' => Favorites::class,
            'notifications' => Notifications::class,
            'request-account' => RequestAccount::class,
            'request-reset' => RequestReset::class,
            'reset-password' => ResetPassword::class,
            'password' => Password::class,
            'roles' => Roles::class,
            'create' => Create::class,
            'delete' => [
                'class' => DeleteAction::class,
                'query' => User::find(),
            ],
        ];
    }

    public function behaviors(): array
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'access' => [
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'accept-invitation',
                            'confirm-invitation',
                            'create',
                            'request-account',
                            'reset-password',
                            'request-reset',
                        ],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'actions' => [
                            'access-requests',
                            'confirm-email',
                            'email',
                            'favorites',
                            'newsletters',
                            'notifications',
                            'password',
                            'profile',
                            'select-2',
                        ],
                    ],
                ],
            ],
            HmacFilter::class => [
                'class' => HmacFilter::class,
                'signer' => \Yii::$app->urlSigner,
                'only' => [
                    'accept-invitation',
                    'confirm-email',
                    'confirm-invitation',
                    'reset-password',
                ],
            ],
        ]);
    }
}
