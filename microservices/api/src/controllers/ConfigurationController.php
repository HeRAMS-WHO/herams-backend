<?php

declare(strict_types=1);

namespace herams\api\controllers;

use herams\common\helpers\ConfigurationProvider;
use League\ISO3166\ISO3166;
use yii\web\Response;

class ConfigurationController extends Controller
{
    public function actionCountries(Response $response): array
    {
        $response->headers->add('Cache-Control', 'max-age=604800,public');
        return (new ISO3166())->all();
    }

    public function actionLocales(
        ConfigurationProvider $configurationProvider,
        Response $response): array|string
    {
        $response->headers->add('Cache-Control', 'max-age=10,public');
        return $configurationProvider->getPlatformLocales();


    }
}
