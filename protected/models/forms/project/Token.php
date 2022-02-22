<?php

namespace prime\models\forms\project;

use Carbon\Carbon;
use SamIT\LimeSurvey\Interfaces\WritableTokenInterface;
use yii\base\Model;
use yii\validators\DateValidator;
use yii\validators\NumberValidator;
use yii\validators\SafeValidator;

/**
 * Class Token
 * This model wraps a WritableTokenInterface object in a Yii2 form model.
 * @package prime\models\forms\projects
 */
class Token extends Model
{
    /**
     * @var WritableTokenInterface
     */
    protected $_token;

    public function __construct(WritableTokenInterface $token, array $config = [])
    {
        parent::__construct($config);
        $this->_token = $token;
    }


    public function __get($name)
    {
        $getter = 'get' . ucfirst($name);
        if (method_exists($this->_token, $getter)) {
            return $this->_token->$getter();
        } elseif (array_key_exists(ucfirst($name), $this->_token->getCustomAttributes())) {
            return $this->_token->getCustomAttributes()[ucfirst($name)];
        } else {
            return parent::__get($name);
        }
    }

    public function __isset($name)
    {
        $getter = 'get' . ucfirst($name);
        if (method_exists($this->_token, $getter)) {
            return $this->$getter() !== null;
        } else {
            return parent::__isset($name);
        }
    }

    public function setValidFrom($string)
    {
        $this->_token->setValidFrom(!empty($string) ? new Carbon($string) : null);
    }

    public function setValidUntil($string)
    {
        $this->_token->setValidUntil(!empty($string) ? new Carbon($string) : null);
    }
    public function __set($name, $value)
    {
        $setter = 'set' . ucfirst($name);
        // Check local setters first.
        if (method_exists($this, $setter)) {
            $this->$setter($value);
        } elseif (method_exists($this->_token, $setter)) {
            return $this->_token->$setter($value);
        } elseif (array_key_exists(ucfirst($name), $this->_token->getCustomAttributes())) {
            return $this->_token->setCustomAttribute(ucfirst($name), $value);
        } else {
            return parent::__set($name, $value);
        }
    }

    public function attributes()
    {
        $attributes = $this->_token->getCustomAttributes();
        foreach (get_class_methods(WritableTokenInterface::class) as $method) {
            if (preg_match('/(get|set)Custom.*/', $method)) {
                continue;
            } elseif (strncmp('set', $method, 3) === 0) {
                $attributes[substr($method, 3)] = true;
            } elseif (strncmp('get', $method, 3) === 0) {
                $attributes[substr($method, 3)] = true;
            }
        };
        return array_map('lcfirst', array_keys($attributes));
    }

    public function isCustomAttribute($name)
    {
        return array_key_exists($name, $this->_token->getCustomAttributes());
    }

//    public function canGetProperty($name, $checkVars = true, $checkBehaviors = true)
//    {
//        return array_key_exists($name, $this->_attributes) || parent::canGetProperty($name, $checkVars, $checkBehaviors);
//    }
//
//    public function canSetProperty($name, $checkVars = true, $checkBehaviors = true)
//    {
//        return array_key_exists($name, $this->_attributes) || parent::canSetProperty($name, $checkVars, $checkBehaviors);
//    }



    public function rules()
    {
        return [
            [['firstName', 'lastName'], SafeValidator::class],
            [array_map('lcfirst', array_keys($this->_token->getCustomAttributes())), SafeValidator::class],
            [['validFrom', 'validUntil'], DateValidator::class, 'format' => 'php:Y-m-d H:i:s'],
            [['usesLeft'], NumberValidator::class, 'min' => 1]
        ];
    }
    /**
     * Save the token to limesurvey.
     */
    public function save($runValidation = true)
    {
        return (!$runValidation || $this->validate()) && $this->_token->save();
    }

    /**
     * @return WritableTokenInterface The token object that this form model wraps.
     */
    public function getToken()
    {
        return $this->_token;
    }

    public function attributeHints()
    {
        return [
            'firstName' => \Yii::t('app', 'We use this field to store the country for the project'),
            'lastName' => \Yii::t('app', 'We use this field to store the last name of the project owner')
        ];
    }
}
