<?php

namespace app\components;

use yii\helpers\ArrayHelper;
use yii\web\View;

class InlineView extends View
{
    public function registerJsFile($url, $options = [], $key = null)
    {
        $depends = ArrayHelper::getValue($options, 'depends', []);

        if (empty($depends)) {
            $position = ArrayHelper::remove($options, 'position', self::POS_END);
            if (strncmp($url, '/', 1) === 0) {
                $fileName = \Yii::getAlias('@webroot') . explode('?', $url)[0];
                if (file_exists($fileName)) {
                    $this->registerJs(file_get_contents($fileName), $position, $fileName);
                }
            }
        } else {
            parent::registerJsFile($url, $options, $key);
        }
    }
}