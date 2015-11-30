<?php
namespace prime\models\forms\projects;

use yii\base\Model;


class Token extends Model
{
    /**
     * @var string The token
     */
    public $token;
    /**
     * @var string The firstname, we store the project title here.
     */
    public $firstname;

    /**
     * @var string The lastname, we don't use this.
     */
    public $lastname;

    /**
     * @var string The email, we don't use this.
     */
    public $email;

    /**
     * @var string validfrom
     */
    public $validfrom;

    protected $_labels = [];

    protected $_attributes = [];

    public function __get($name)
    {
        if (array_key_exists($name, $this->_attributes)) {
            return $this->_attributes[$name];
        } else {
            return parent::__get($name);
        }
    }

    public function __isset($name)
    {
        return isset($this->_attributes[$name]) || parent::__isset($name);
    }

    public function __set($name, $value)
    {
        if (array_key_exists($name, $this->_attributes)) {
            $this->_attributes[$name] = $value;
        } else {
            parent::__set($name, $value);
        }
    }


    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), $this->_labels);
    }

    public function attributes()
    {
        return array_merge(array_keys($this->_attributes), parent::attributes());
    }

    public function canGetProperty($name, $checkVars = true, $checkBehaviors = true)
    {
        return array_key_exists($name, $this->_attributes) || parent::canGetProperty($name, $checkVars, $checkBehaviors);
    }

    public function canSetProperty($name, $checkVars = true, $checkBehaviors = true)
    {
        return array_key_exists($name, $this->_attributes) || parent::canSetProperty($name, $checkVars, $checkBehaviors);
    }

    public function loadAttributesFromDescriptions(array $descriptions)
    {
        foreach($descriptions as $key => $description) {
            $this->_labels[$key] = $description['description'];
            $this->_attributes[$key] = null;
        }
    }

}