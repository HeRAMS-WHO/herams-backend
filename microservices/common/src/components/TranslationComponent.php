<?php

namespace herams\common\components;

use herams\common\extensions\ExtendedGettextMessageSource;
use Yii;
use yii\base\Component;

class TranslationComponent extends Component
{
    /**
     * Get translations for a specific category and language.
     *
     * @param string $category
     * @param string $language
     * @return array
     */
    public function getTranslations($category = 'app', $language = null)
    {
        if ($language === null) {
            $language = Yii::$app->language;
        }

        $i18n = Yii::$app->i18n;
        $source = $i18n->getMessageSource('yii');

        if ($source instanceof ExtendedGettextMessageSource) {
            $messages = $source->publicLoadMessages($category, $language);
            return $messages;
        }
        $sources = new \herams\common\extensions\ExtendedGettextMessageSource();

        $messages = $source->publicLoadMessages($category, $language);

        return $messages;
    }
}
