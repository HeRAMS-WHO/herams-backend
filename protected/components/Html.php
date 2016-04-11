<?php

namespace app\components;

class Html extends \yii\bootstrap\Html
{


    public static function textImage($text) {
        return '<svg xmlns="http://www.w3.org/2000/svg" version="1.1" height="100" width="100"><text x="0" y="50" fill="#666" style="font-size: 50px; alignment-baseline: middle;">' . $text . '</text></svg>';
    }

}