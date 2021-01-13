<?php

namespace prime\components;

use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class Controller extends \yii\web\Controller
{
    public const LAYOUT_FORM_POPOVER = '//form-popover';
    public const LAYOUT_ADMIN = '//admin-screen';
    public const LAYOUT_ADMIN_CONTENT = '//admin-content';
    public const LAYOUT_ADMIN_TABS = '//admin-tabs';
    public const LAYOUT_ADMIN_NO_TABS = '//admin-notabs';

    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['admin'],
                        ],
                    ]
                ]
            ]
        );
    }
}
