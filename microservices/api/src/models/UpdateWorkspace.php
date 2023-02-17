<?php

declare(strict_types=1);

namespace herams\api\models;

use herams\common\helpers\LocalizedString;
use herams\common\models\RequestModel;
use herams\common\values\WorkspaceId;
use yii\validators\RequiredValidator;

final class UpdateWorkspace extends RequestModel
{
    public LocalizedString|null $title;

    /**
     * @var array|null Any other survey data
     */
    public array|null $data = null;

    public function __construct(
        public readonly WorkspaceId $id
    ) {
        parent::__construct();
    }

    public function rules(): array
    {
        return [
            [['title'], RequiredValidator::class],

        ];
    }
}
