<?php

declare(strict_types=1);

namespace prime\components;

use prime\objects\enums\Language;
use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\web\Request;

class LanguageSelector implements BootstrapInterface
{
    /**
     * @param Application $app
     */
    public function bootstrap($app)
    {
        $app->on(\yii\web\Application::EVENT_BEFORE_ACTION, function () use ($app) {
            try {
                if ($app->request->getQueryParam('_lang')
                    && null !== $language = Language::tryFrom($app->request->getQueryParam('_lang'))
                ) {
                    $app->language = $language->value;
                    return;
                }
                if (!$app->user->isGuest && $app->user->identity->preferredLanguage) {
                    $app->language = $app->user->identity->preferredLanguage->value;
                    return;
                }
            } catch (\BadMethodCallException) {
                // Unknown language; use autodetection.
            }
            $app->language = $this->getPreferredLanguage($app->request);
        });
    }

    public function getPreferredLanguage(Request $request): string
    {
        // We implement our own logic for determining the preferred language. The Yii implementation doesn't prioritize properly.
        foreach($request->getAcceptableLanguages() as $acceptableLanguage) {
            if (Language::tryFrom($acceptableLanguage) !== null) {
                return Language::from($acceptableLanguage)->value;
            }
        };
        return Language::default()->value;
    }
}
