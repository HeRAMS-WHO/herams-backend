<?php
declare(strict_types=1);

namespace prime\models;

use yii\base\Model;

abstract class RequestModel extends Model
{

    final public function load($data, $formName = null): bool
    {
        return parent::load($data, '');
    }
}
