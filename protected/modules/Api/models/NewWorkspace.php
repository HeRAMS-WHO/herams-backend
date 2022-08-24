<?php

declare(strict_types=1);

namespace prime\modules\Api\models;

use prime\helpers\LocalizedString;
use prime\interfaces\ValidationErrorCollection;
use prime\models\RequestModel;
use prime\values\ProjectId;
use yii\base\Model;
use yii\validators\RequiredValidator;

final class NewWorkspace extends RequestModel
{
    public null|LocalizedString $title = null;

    public null|ProjectId $projectId = null;

    public function rules(): array
    {
        return [
            [['title', 'projectId'], RequiredValidator::class],
        ];
    }
}
