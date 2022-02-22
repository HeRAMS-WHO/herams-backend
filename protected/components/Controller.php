<?php

declare(strict_types=1);

namespace prime\components;

use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

/**
 * Class Controller
 * @package prime\components
 *
 * @property View $view
 */
class Controller extends \yii\web\Controller
{
    public const LAYOUT_BASE = '//base';
    public const LAYOUT_FORM_POPOVER = '//form-popover';
    public const LAYOUT_ADMIN = '//admin-screen';
    public const LAYOUT_ADMIN_CONTENT = '//admin-content';
    public const LAYOUT_ADMIN_TABS = '//admin-tabs';
    public const LAYOUT_CSS3_GRID = '//css3-grid';
    public const LAYOUT_MAP_POPOVER_ERROR = '//map-popover-error';
    public const LAYOUT_MAINTENANCE = '//maintenance';

    public function behaviors(): array
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
