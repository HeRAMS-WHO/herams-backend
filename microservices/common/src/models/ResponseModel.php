<?php

declare(strict_types=1);

namespace herams\common\models;

use yii\base\Model;
use yii\base\NotSupportedException;

abstract class ResponseModel extends Model
{
    public function __construct()
    {
        parent::__construct([]);
    }

    final public function formName(): string
    {
        return '';
    }

    final public function load($data, $formName = null): bool
    {
        return parent::load($data, '');
    }

    final public function validate($attributeNames = null, $clearErrors = null): bool
    {
        throw new NotSupportedException('A response model MUST not use validation');
    }

    final public function rules(): array
    {
        return [];
    }

}
