<?php
declare(strict_types=1);

namespace prime\components;

use prime\objects\enums\Language;
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
            try {
                if ($app->request->getQueryParam('_lang')) {
                    $app->language = Language::make($app->request->getQueryParam('_lang'))->value;
                    return;
                }
                if (!$app->user->isGuest && $app->user->identity->language) {
                    $app->language = Language::make($app->user->identity->language)->value;
                    return;
                }
            } catch (\BadMethodCallException) {
                // Unknown language; use autodetection.
            }


            $app->language = (string) $app->request->getPreferredLanguage(Language::cases());
        });
    }
}
