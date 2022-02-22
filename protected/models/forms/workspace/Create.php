<?php

declare(strict_types=1);

namespace prime\models\forms\workspace;

use prime\models\ar\Workspace;
use prime\values\ProjectId;
use yii\base\Model;
use yii\validators\FilterValidator;
use yii\validators\RequiredValidator;
use yii\validators\StringValidator;

class Create extends Model
{
    public null|string $title = null;
    public null|ProjectId $project_id = null;

    public function attributeLabels(): array
    {
        return Workspace::labels();
    }

    public function rules(): array
    {
        return [
            [['title'], FilterValidator::class, 'filter' => 'trim'],
            [['title'], RequiredValidator::class],
            [['title'], StringValidator::class],
        ];
    }
}
