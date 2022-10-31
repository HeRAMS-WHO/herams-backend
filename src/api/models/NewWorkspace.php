<?php

declare(strict_types=1);

namespace herams\api\models;

use prime\helpers\LocalizedString;
use prime\models\RequestModel;
use prime\values\ProjectId;
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
