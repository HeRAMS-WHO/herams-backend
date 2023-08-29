<?php

namespace herams\common\components;

class Application extends \yii\web\Application
{
    private $_appVersion;

    public function getAppVersion(): string
    {
        if ($this->_appVersion === null) {
            $this->_appVersion = file_exists(\Yii::getAlias('@app/../version.txt'))
                ? trim(file_get_contents(\Yii::getAlias('@app/../version.txt')))
                : 'unknown';
        }
        return $this->_appVersion;
    }
}
