<?php

namespace yii\helpers;

use Befound\Components\Map;

class Html extends BaseHtml
{
    public static function checkboxList($name, $selection = null, $items = [], $options = [])
    {
        if($selection instanceof Map) {
            $selection = $selection->asArray();
        }
        return parent::checkboxList($name,$selection,$items,$options);
    }
}