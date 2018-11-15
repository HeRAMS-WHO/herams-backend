<?php

namespace prime\models;

use prime\components\ActiveQuery;
use prime\injection\SetterInjectionInterface;
use prime\injection\SetterInjectionTrait;
use prime\models\ar\User;
use yii\web\HttpException;

class ActiveRecord extends \yii\db\ActiveRecord implements SetterInjectionInterface
{
    use SetterInjectionTrait;
    /**
     * @return ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public static function find()
    {
        $class = "app\\queries\\" . (new \ReflectionClass(get_called_class()))->getShortName() . 'Query';
        if (!class_exists($class)) {
            $class = ActiveQuery::class;
        }
        return \Yii::createObject($class, [get_called_class()]);
    }

    public function loadFromAttributeData()
    {
        $criteria = array_filter($this->attributes, function($value) {return $value !== null;});
        $results = $this::findAll($criteria);

        switch(count($results)) {
            case 0:
                $result = $this;
                break;
            case 1:
                $result = $results[0];
                break;
            default:
                throw new \Exception('Multiple records found');
        }

        return $result;
    }

    /**
     * Loads a model, throws an exception if user does not have permission.
     * @param int $id
     * @param string $priv
     * @param array $with
     * @return static ::class
     * @throws HttpException
     */
    public static function loadOne($id, array $with = []) {
        $result = static::find()->where(['id' => $id])->with($with)->one();
        if (!isset($result)) {
            throw new HttpException(404, \yii\helpers\StringHelper::basename(static::class) . " not found.");
        }
        return $result;
    }

    public function userCan($operation, User $user)
    {
        // Admins can do anything
        return app()->authManager->checkAccess($user->id, 'admin');
    }





    /**
     * This static function is responsible for creation in Yii.
     * It therefore requires \Yii.
     * @param array $row
     * @return static
     */
    public static function instantiate($row)
    {
        $result = parent::instantiate($row);
        if ($result instanceof SetterInjectionInterface) {
            // Inject required dependencies.
            foreach($result::listDependencies() as $setter => $class) {
                $result->$setter(\Yii::$container->get($class));
            }
        }
        return $result;
    }

    /**
     * Returns a field useful for displaying this record
     * @return string
     */
    public function getDisplayField()
    {
        foreach ($this->attributes as $name)
        {
            if (in_array($name, array('title', 'name')))
            {
                return $this->getAttribute($name);
            }
        }
        $pk = $this->getPrimaryKey();
        if (is_array($pk))
        {
            $pk = print_r($pk, true);
        }


        return "No title for " . get_class($this) . "($pk)";
    }
}