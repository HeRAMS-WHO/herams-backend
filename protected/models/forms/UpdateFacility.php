<?php
declare(strict_types=1);

namespace prime\models\forms;

use prime\attributes\DehydrateVia;
use prime\attributes\HydrateVia;
use prime\interfaces\WorkspaceForNewOrUpdateFacility;
use prime\values\FacilityId;
use prime\values\Point;
use yii\base\Model;
use yii\validators\DefaultValueValidator;
use yii\validators\RequiredValidator;
use yii\validators\StringValidator;

final class UpdateFacility extends Model
{
    #[HydrateVia(Point::class)]
    #[DehydrateVia(Point::class)]
    public null|string $coordinates = null;

    public null|string $name = null;
    public null|string $alternative_name = null;
    public null|string $code = null;


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

    public function attributeLabels(): array
    {
        return [
            'name' => \Yii::t('app', 'Name'),
            'alternative_name' => \Yii::t('app', 'Alternative name'),
            'code' => \Yii::t('app', 'Code'),
            'coordinates' => \Yii::t('app', 'Coordinates'),
        ];
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
