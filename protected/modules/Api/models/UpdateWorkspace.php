<?php

declare(strict_types=1);

namespace prime\modules\Api\models;

use prime\helpers\LocalizedString;
use prime\models\RequestModel;
use prime\values\WorkspaceId;
use yii\validators\RequiredValidator;

final class UpdateWorkspace extends RequestModel
{
    public LocalizedString|null $title;

    /**
     * @var array|null Any other survey data
     */
    public array|null $data = null;

    public function __construct(public readonly WorkspaceId $id)
    {
        parent::__construct();
    }

    public function rules(): array
    {
        return [
            [['title'], RequiredValidator::class],

        ];
    }
}
