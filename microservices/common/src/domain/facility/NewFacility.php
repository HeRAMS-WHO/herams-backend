<?php

declare(strict_types=1);

namespace herams\common\domain\facility;

use Collecthor\DataInterfaces\RecordInterface;
use herams\common\attributes\SourcePath;
use herams\common\helpers\LocalizedString;
use herams\common\models\RequestModel;
use herams\common\values\WorkspaceId;
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