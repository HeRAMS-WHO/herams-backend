<?php

namespace prime\components;

class VarDumper extends \yii\helpers\VarDumper {

    public static function dump($var, $depth = 10, $highlight = true)
    {
        parent::dump($var, $depth, $highlight);
    }


}