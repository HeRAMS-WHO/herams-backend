<?php
declare(strict_types=1);

namespace prime\models\forms;


use prime\values\Point;
use prime\values\WorkspaceId;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use yii\base\Model;
use yii\validators\DefaultValueValidator;
use yii\validators\RequiredValidator;
use yii\validators\SafeValidator;
use yii\validators\StringValidator;

/**
 * Class Facility
 * @package prime\models\forms
 * @property-read WorkspaceId $workspace_id
 */
class Facility extends Model
{
    public Point|null $coords = null;
    public UuidInterface $uuid;

    public null|string $name = null;
    public null|string $alternative_name = null;
    public null|string $code = null;

    public function __construct(private WorkspaceId $workspace_id)
    {
        parent::__construct();
        $this->uuid = Uuid::uuid6();
    }

    public function attributes(): array
    {
        $result = parent::attributes();
        $result[] = 'workspace_id';
        return $result;
    }

    public function getWorkspace_Id(): WorkspaceId
    {
        return $this->workspace_id;
    }


    public function rules()
    {
        return [
            [['name'], RequiredValidator::class],
            [['code'], StringValidator::class],
            [['alternative_name'], StringValidator::class],
            [['code', 'coords'], SafeValidator::class],
            [['code'], DefaultValueValidator::class]
        ];
    }


}
