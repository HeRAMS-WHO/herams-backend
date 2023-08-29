<?php

declare(strict_types=1);

namespace herams\common\domain\facility;

use Collecthor\DataInterfaces\RecordInterface;
use herams\common\attributes\SourcePath;
use herams\common\helpers\LocalizedString;
use herams\common\models\RequestModel;
use herams\common\values\DatetimeValue;
use herams\common\values\WorkspaceId;
use yii\validators\RequiredValidator;

final class NewFacility extends RequestModel
{
    public null|WorkspaceId $workspaceId = null;

    public null|RecordInterface $situationData = null;

    public null|RecordInterface $adminData = null;

    #[SourcePath(['adminData', 'name'])]
    public LocalizedString|null $name = null;

    public int|null $createdBy = null;

    public int|null $lastModifiedBy = null;

    public DatetimeValue|null $createdDate = null;

    public DatetimeValue|null $lastModifiedDate = null;

    public function rules(): array
    {
        return [
            [['workspaceId', 'situationData', 'adminData', 'name', 'createdBy', 'createdDate', 'lastModifiedBy', 'lastModifiedDate'], RequiredValidator::class],
        ];
    }
}
