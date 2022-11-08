<?php

declare(strict_types=1);

namespace herams\common\models;

use yii\base\Model;
use yii\base\NotSupportedException;

abstract class RequestModel extends Model
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

    public function rules(): array
    {
        throw new NotSupportedException('Do not call parent::rules() in your request model');
    }

    final public function validate($attributeNames = null, $clearErrors = null): bool
    {
        if (isset($attributeNames)) {
            throw new NotSupportedException("Don't use this");
        }

        if ($clearErrors === true) {
            throw new NotSupportedException("Don't use clear errors, if needed clear errors manually");
        }
        return parent::validate($attributeNames, false);
    }
}
