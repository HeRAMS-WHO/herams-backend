<?php

namespace herams\common\extensions;

use yii\i18n\GettextMessageSource;

class ExtendedGettextMessageSource extends GettextMessageSource
{
    public function publicLoadMessages($category, $language)
    {
        $test = $this->loadMessages($category, $language);
        return $test;
    }
}
