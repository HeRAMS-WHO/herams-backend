<?php
declare(strict_types=1);

namespace prime\components;

use yii\base\Application;
use yii\base\BootstrapInterface;

class LanguageSelector implements BootstrapInterface
{
    /**
     * @param Application $app
     */
    public function bootstrap($app)
    {
        $app->on(\yii\web\Application::EVENT_BEFORE_ACTION, static function () use ($app) {
            if (!$app->user->isGuest && isset($app->user->identity->language)) {
                $app->language = $app->user->identity->language;
            } else {
                $app->language = $app->request->getPreferredLanguage($app->params['languages']);
            }
        });
    }
}
