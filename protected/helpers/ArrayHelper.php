<?php

declare(strict_types=1);

namespace prime\helpers;

use ArrayAccess;
use yii\helpers\BaseArrayHelper;

class ArrayHelper extends BaseArrayHelper
{
    public static function getValue($array, $key, $default = null)
    {
        if ($key instanceof \Closure) {
            return $key($array, $default);
        }

        if (is_array($key)) {
            $lastKey = array_pop($key);
            foreach ($key as $keyPart) {
                $array = static::getValue($array, $keyPart);
            }
            $key = $lastKey;
        }

        $getter = 'get' . ucfirst($key);
        if (is_object($array)) {
            if (method_exists($array, $getter)) {
                return $array->$getter();
            } elseif (property_exists($array, $key)) {
                return $array->$key;
            }
        }
        if (static::keyExists($key, $array)) {
            return $array[$key];
        }

        if (($pos = strrpos($key, '.')) !== false) {
            $array = static::getValue($array, substr($key, 0, $pos), $default);
            $key = substr($key, $pos + 1);
        }

        if (static::keyExists($key, $array)) {
            return $array[$key];
        }
        if (is_object($array)) {
            // this is expected to fail if the property does not exist, or __get() is not implemented
            // it is not reliably possible to check whether a property is accessible beforehand
            try {
                return $array->$key;
            } catch (\Exception $e) {
                if ($array instanceof ArrayAccess) {
                    return $default;
                }
                throw $e;
            }
        }

        return $default;
    }
}
