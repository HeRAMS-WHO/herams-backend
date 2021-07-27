<?php
declare(strict_types=1);

namespace prime\models\forms;

use prime\attributes\DehydrateVia;
use prime\interfaces\WorkspaceForNewOrUpdateFacility;
use prime\models\ar\Facility;
use prime\traits\DisableYiiLoad;
use prime\values\Point;
use yii\base\Model;
use yii\validators\RequiredValidator;
use yii\validators\StringValidator;

final class NewFacility extends Model
{
    use DisableYiiLoad;
    #[DehydrateVia(Point::class)]
    public null|string $coordinates = null;

    public null|string $name = null;
    public null|string $alternative_name = null;
    public null|string $code = null;


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

    public function rules(): array
    {
        return [
            [['name'], RequiredValidator::class],
            [['code', 'name', 'alternative_name'], StringValidator::class, 'max' => 100, 'min' => 3],
            Point::validatorFor('coordinates'),
        ];
    }
}
