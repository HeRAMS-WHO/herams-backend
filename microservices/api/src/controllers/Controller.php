<?php

declare(strict_types=1);

namespace herams\api\controllers;

use herams\common\helpers\ConfigurationProvider;
use herams\common\helpers\Locale;
use yii\filters\AccessControl;
use yii\filters\ContentNegotiator;
use yii\web\Response;
use function iter\map;
use function iter\toArray;

abstract class Controller extends \yii\rest\Controller
{
    public $enableCsrfValidation = false;

    public function __construct(
        $id,
        $module,
        private ConfigurationProvider $configurationProvider,
        $config = []
    ) {
        parent::__construct($id, $module, $config);
    }

    public function behaviors(): array
    {
        $locales = toArray(map(fn (Locale $locale) => $locale->locale, $this->configurationProvider->getPlatformLocales()));
        return [
            ...parent::behaviors(),
            'contentNegotiator' => [
                'class' => ContentNegotiator::class,
                'languages' => $locales,
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => false,
                    ],
                ],
            ],
        ];
    }
}
