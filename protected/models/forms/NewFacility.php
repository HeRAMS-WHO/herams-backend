<?php
declare(strict_types=1);

namespace prime\models\forms;

use prime\attributes\DehydrateVia;
use prime\interfaces\WorkspaceForNewOrUpdateFacility;
use prime\values\Point;
use yii\base\Model;
use yii\validators\DefaultValueValidator;
use yii\validators\RegularExpressionValidator;
use yii\validators\RequiredValidator;
use yii\validators\StringValidator;

final class NewFacility extends Model
{
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

    public function rules()
    {
        return [
            [['name'], RequiredValidator::class],
            [['code'], StringValidator::class],
            [['alternative_name'], StringValidator::class],
            Point::validatorFor('coordinates'),
            [['code'], DefaultValueValidator::class]
        ];
    }
}
