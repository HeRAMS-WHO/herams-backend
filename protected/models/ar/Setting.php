<?php
namespace prime\models\ar;

use prime\models\ActiveRecord;
use yii\validators\RequiredValidator;
use yii\validators\StringValidator;

class Setting extends ActiveRecord
{
    protected static $config = [];
    protected static $loaded = false;

    protected static function loadAll()
    {
        if (!self::$loaded) {
            self::$loaded = true;

            foreach (self::find()->all() as $setting) {
                self::cache($setting);
            }
        }
    }

    protected static function cache(Setting $setting)
    {
        return self::$config[$setting->key] = json_decode($setting->value, true);
    }

    public function getDecodedValue()
    {
        return json_decode($this->value, true);
    }

    public static function get($key, $default = null) {
        self::loadAll();
        // Load configuration.
        if (!array_key_exists($key, self::$config)) {
            self::$config[$key] =
                (null !== $model = self::findOne(['key' => $key]))
                    ? self::cache($model) : $default;
        }

        return self::$config[$key];
    }

    public static function set($key, $value) {
        // Get existing.
        $model = self::findOne(['key' => $key]);

        if (!isset($model)) {
            $model = new self();
        }

        $model->key = $key;
        $model->value = json_encode($value);
        self::$config[$key] = $value;
        return $model->save();
    }

    public function rules()
    {
        return [
            [['key'], RequiredValidator::class],
            [['value'], StringValidator::class]
        ];
    }
}