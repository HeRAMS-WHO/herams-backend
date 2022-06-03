<?php

declare(strict_types=1);

namespace prime\modules\Api\models;

use prime\helpers\LocalizedString;
use prime\values\ProjectId;
use yii\base\Model;
use yii\validators\RequiredValidator;

class NewWorkspace extends Model
{
    public LocalizedString $title;

    public ProjectId $projectId;

    public function rules()
    {
        return [
            [['title', 'projectId'], RequiredValidator::class],
        ];
    }
}
