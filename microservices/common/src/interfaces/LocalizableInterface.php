<?php

namespace herams\common\interfaces;

use herams\common\helpers\Locale;

interface LocalizableInterface
{
    public function toLocalizedArray(Locale $locale): array;
}