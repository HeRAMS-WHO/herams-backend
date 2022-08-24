<?php
declare(strict_types=1);

namespace prime\modules\Api\models;

use Collecthor\DataInterfaces\RecordInterface;
use Collecthor\DataInterfaces\VariableInterface;
use prime\attributes\SourcePath;
use prime\helpers\LocalizedString;
use prime\models\RequestModel;
use prime\values\WorkspaceId;
use yii\validators\RequiredValidator;

final class NewFacility extends RequestModel
{
    public null|WorkspaceId $workspaceId = null;

    public null|RecordInterface $data = null;

    #[SourcePath(['data', 'name'])]
    public LocalizedString|null $name = null;

    public function rules(): array
    {
        return [
            [['workspaceId', 'data', 'name'], RequiredValidator::class],
        ];
    }

}
