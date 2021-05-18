<?php
declare(strict_types=1);

namespace prime\models\forms;

use prime\interfaces\WorkspaceForNewFacility;
use prime\values\FacilityId;
use prime\values\Point;
use yii\base\Model;
use yii\validators\DefaultValueValidator;
use yii\validators\RegularExpressionValidator;
use yii\validators\RequiredValidator;
use yii\validators\StringValidator;

final class UpdateFacility extends Model
{
    public null|Point $coords = null;

    public null|string $name = null;
    public null|string $alternative_name = null;
    public null|string $code = null;


    public function __construct(
        private FacilityId $id,
        private WorkspaceForNewFacility $workspace)
    {
        parent::__construct();
    }

    public function getId(): FacilityId
    {
        return $this->id;
    }

    public function getWorkspace(): WorkspaceForNewFacility
    {
        return $this->workspace;
    }

    public function rules()
    {
        return [
            [['name'], RequiredValidator::class],
            [['code'], StringValidator::class],
            [['alternative_name'], StringValidator::class],
            [['coords'], RegularExpressionValidator::class, 'pattern' =>
                "/^\s*\(\s*(-?\d*(\.\d*)?)\s*,\s*(-?\d*(\.\d*)?)\s*\)$/"],
            [['code'], DefaultValueValidator::class]
        ];
    }


}
