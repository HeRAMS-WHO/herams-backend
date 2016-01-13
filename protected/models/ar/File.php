<?php

namespace prime\models\ar;

use prime\models\ActiveRecord;
use yii\validators\RequiredValidator;
use yii\validators\StringValidator;

class File extends ActiveRecord
{
    public function rules()
    {
        return [
            [['mime_type', 'data'], RequiredValidator::class],
            [['name', 'mime_type'], StringValidator::class]
        ];
    }
}