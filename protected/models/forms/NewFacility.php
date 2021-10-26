<?php

declare(strict_types=1);

namespace prime\models\forms;

use prime\attributes\DehydrateVia;
use prime\interfaces\WorkspaceForNewOrUpdateFacility;
use prime\models\ar\Facility;
use prime\traits\DisableYiiLoad;
use prime\values\Point;
use yii\base\InvalidCallException;
use yii\base\Model;
use yii\base\UnknownPropertyException;
use yii\validators\RequiredValidator;
use yii\validators\SafeValidator;
use yii\validators\StringValidator;

final class NewFacility extends Model
{
    use DisableYiiLoad;

    public array $data = [];

    public function __construct(private WorkspaceForNewOrUpdateFacility $workspace)
    {
        parent::__construct();
    }

    public function getWorkspace(): WorkspaceForNewOrUpdateFacility
    {
        return $this->workspace;
    }

    public function attributeLabels(): array
    {
        return Facility::labels();
    }

    public function attributes(): array
    {
        return ['data'];
    }

    public function rules(): array
    {
        return [
            [['data'], SafeValidator::class],
//            [['data.name'], RequiredValidator::class],
//            [['code', 'name', 'alternative_name'], StringValidator::class, 'max' => 100, 'min' => 3],
//            Point::validatorFor('coordinates'),
        ];
    }
}
