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
use yii\validators\StringValidator;

final class UpdateFacility extends Model
{
    use DisableYiiLoad;
    #[HydrateVia(Point::class)]
    #[DehydrateVia(Point::class)]
    public null|string $coordinates = null;

    public null|string $name = null;
    public null|string $alternative_name = null;
    public null|string $code = null;

    public null|array $i18n = null;

    public function __construct(
        private FacilityId $id,
        private WorkspaceForNewOrUpdateFacility $workspace
    ) {
        parent::__construct();
    }

    public function behaviors(): array
    {
        return [
            LocalizableWriteBehavior::class => [
                'class' => LocalizableWriteBehavior::class,
                'attributes' => ['name', 'alternative_name']
            ]
        ];
    }

    public function getId(): FacilityId
    {
        return $this->id;
    }

    public function attributeLabels(): array
    {
        return Facility::labels();
    }


    public function getWorkspace(): WorkspaceForNewOrUpdateFacility
    {
        return $this->workspace;
    }


    public function rules(): array
    {
        return [
            [['name'], RequiredValidator::class],
            [['code', 'name', 'alternative_name'], StringValidator::class, 'max' => 100, 'min' => 3],
            Point::validatorFor('coordinates')
        ];
    }
}