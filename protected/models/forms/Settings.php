<?php


namespace prime\models\forms;

use prime\models\Setting;
use yii\base\Model;

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

    public function rules()
    {
        return [
            ['limesurvey.host', 'url']
        ];
    }

    public function init() {
        foreach(Setting::find()->all() as $setting) {
            $this->data[$setting->key] = $setting->decodedValue;
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
            parent::__get($name, $value);
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