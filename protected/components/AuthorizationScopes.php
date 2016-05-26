<?php

namespace prime\components;

use app\queries\ProjectQuery;
use prime\models\ar\User;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\web\HttpException;

trait AuthorizationScopes
{
    private $_operations = [];
    private $_required = false;

    public function userCan($operation, $user = null)
    {
        $userId = ($user instanceof User) ? $user->id : $user;

        if (!app()->user->identity->isAdmin) {
            $this->_required = true;
            $modelClass = $this->modelClass;
            //die($required);
            if (!method_exists($modelClass, 'userCanScope') || !$modelClass::userCanScope($this, $operation, $user)) {
                $this->_operations[] = [$operation, $userId];
            }
        }
        return $this;
    }

    public function all($db = null)
    {
        $results = parent::all($db);
        return empty($this->_operations) ? $results :
            array_filter($results, function($element) {
                foreach ($this->_operations as $params) {
                    $userCanResult = $element->userCan($params[0], $params[1]);
                    if(!is_bool($userCanResult)) {
                        throw new \Exception('userCan must be boolean, did you implement it in ' . get_class($this) . '?');
                    }
                    if (!$userCanResult) {
                        return false;
                    }
                }
                return true;
            });
    }

    /**
     *
     * @param string $q
     * @param null $db
     * @return mixed
     */
    public function count($q = '*', $db = null)
    {
        if (!empty($this->_operations)) {
            if ($q === '*') {
                \Yii::info('Doing inefficient count because userCanScope is not implemented.');
                $clone = clone($this);
                return count($clone->all($db));
            } else {
                throw new InvalidConfigException('Custom counts not supported when using authorizationscopes without userCanScope');
            }
        } else {
            return parent::count($q, $db);
        }
    }

    public function one($db = null)
    {
        $element = parent::one($db);
        if ($element) {
            foreach($this->_operations as $params) {
                if (!$element->userCan($params[0], $params[1])) {
                    throw new HttpException(403, "Operation not allowed");
                }
            }
        } elseif ($this->_required) {
            throw new HttpException(403, "Operation not allowed");
        }
        return $element;
    }

    public function column($db = null)
    {

        if($this->select === null || (is_array($this->select) && reset($this->select) === '*')) {
            throw new \Exception('Must specify columns when querying with column()');
        }

        return ArrayHelper::getColumn($this->all(), is_array($this->select) ? reset($this->select) : $this->select);
    }
    /** 
     * From Yii docs:
     * ActiveQuery mainly provides the following methods to retrieve the query results:

        one(): returns a single record populated with the first row of data.
        all(): returns all records based on the query results.
        count(): returns the number of records.
        sum(): returns the sum over the specified column.
        average(): returns the average over the specified column.
        min(): returns the min over the specified column.
        max(): returns the max over the specified column.
        scalar(): returns the value of the first column in the first row of the query result.
        column(): returns the value of the first column in the query result.
        exists(): returns a value indicating whether the query result has data or not.
     * 
     * We should override all of these.
     */
}