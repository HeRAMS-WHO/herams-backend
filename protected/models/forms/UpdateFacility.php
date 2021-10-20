<?php

declare(strict_types=1);

namespace prime\models\forms;

use prime\attributes\DehydrateVia;
use prime\attributes\HydrateVia;
use prime\behaviors\LocalizableWriteBehavior;
use prime\interfaces\WorkspaceForNewOrUpdateFacility;
use prime\models\ar\Facility;
use prime\traits\DisableYiiLoad;
use prime\values\FacilityId;
use prime\values\Point;
use yii\base\Model;
use yii\validators\RequiredValidator;
use yii\validators\SafeValidator;
use yii\validators\StringValidator;

final class UpdateFacility extends Model
{
    use DisableYiiLoad;

    public null|array $data = null;

    public function __construct(
        private FacilityId $id,
        private WorkspaceForNewOrUpdateFacility $workspace
    ) {
        parent::__construct();
    }

    public function getId(): FacilityId
    {
        return $this->id;
    }

    public function getWorkspace(): WorkspaceForNewOrUpdateFacility
    {
        return $this->workspace;
    }

    /**
     * This validates only the absolutely necessary requirements
     * @return array
     */
    public function rules(): array
    {
        return [
            [['data'], SafeValidator::class],
        ];
    }
}
