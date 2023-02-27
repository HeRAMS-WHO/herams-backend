<?php

declare(strict_types=1);

namespace herams\api\domain\workspace;

use herams\common\helpers\LocalizedString;
use herams\common\models\RequestModel;
use herams\common\values\ProjectId;
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
