<?php

namespace prime\models\forms;

use DateTime;
use prime\models\ar\Setting;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\validators\RangeValidator;
use yii\validators\RequiredValidator;

/**
 * Class Settings
 * Model for all site-wide settings.
 * All attributes that are safe are settable.
 *
 * @package models\forms
 */
class Settings extends Model
{
    private $data = [];

    public function attributeLabels() {
        return [

        ];
    }

    public function getAttributeLabel($attribute)
    {
        return strtr(parent::getAttributeLabel($attribute), ['Icons ' => 'Icon: ']);
    }

    public function rules()
    {
        return [
            ['limeSurvey.host', 'url'],
            [['limeSurvey.password', 'limeSurvey.username'], RequiredValidator::class],
        ];
    }

    public function init() {
        foreach(Setting::find()->all() as $setting) {
            $this->data[$setting->key] = $setting->decodedValue;
        }
        foreach(app()->params['defaultSettings'] as $attribute => $value) {
            if(!array_key_exists($attribute, $this->data)) {
                $this->data[$attribute] = $value;
            }
        }

    }

    public function __get($name)
    {
        if ($this->isAttributeSafe($name)) {
            return isset($this->data[$name]) ? $this->data[$name] : null;
        } else {
            return parent::__get($name);
        }
    }

    public function __set($name, $value) {
        if ($this->isAttributeSafe($name)) {
            $this->data[$name] = $value;
        } else {
            parent::__set($name, $value);
        }
    }
    public function save() {
        $transaction = \Yii::$app->db->beginTransaction();
        foreach($this->data as $key => $value) {
            if (!Setting::set($key, $value)) {
                $this->addError($key, "Incorrect value");
            }
        }
        if (!$this->hasErrors()) {
            $transaction->commit();
            return true;
        } else {
            $transaction->rollBack();
            return false;
        }

    }
}