<?php
declare(strict_types=1);

namespace prime\models\ar;

use prime\components\ActiveQuery;
use prime\models\ActiveRecord;
use prime\models\forms\ResponseFilter;
use prime\objects\HeramsCodeMap;
use prime\queries\FacilityQuery;
use prime\queries\ResponseQuery;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use SamIT\Yii2\VirtualFields\VirtualFieldBehavior;
use yii\db\Expression;
use yii\validators\ExistValidator;

/**
 * @property int $id
 * @property string $uuid
 * @property string $name
 * @property array $i18n
 * @property string $alternative_name
 * @property string $code
 * @property int $workspace_id
 * @property null|DateTime $deleted
 * @property null|DateTime $deactivated
 *
 */
class Facility extends ActiveRecord
{
    public static function find(): FacilityQuery
    {
        return new FacilityQuery(static::class);
    }

    public function __construct($config = [])
    {
        parent::__construct($config);
        // TODO: Move to behavior
        if (!isset($this->uuid)) {
            $this->uuid = Uuid::uuid6()->getBytes();
        }
    }

    public function rules(): array
    {
        return [
            [['workspace_id'], ExistValidator::class, 'targetClass' => Workspace::class]
        ];
    }


    public function getResponses(): ResponseQuery
    {
        return $this->hasMany(Response::class, [
            'facility_id' => 'id',
        ])->inverseOf('facility');
    }

    public function getWorkspace(): ActiveQuery
    {
        return $this->hasOne(Workspace::class, [
            'id' => 'workspace_id'
        ]);
    }

    public static function labels(): array
    {
        return [
            'id' => \Yii::t('app', 'Facility ID'),
            'name' => \Yii::t('app', 'Name'),
            'i18n' => \Yii::t('app', 'Localization'),
            'alternative_name' => \Yii::t('app', 'Alternative name'),
            'code' => \Yii::t('app', 'Code'),
            'coordinates' => \Yii::t('app', 'Coordinates'),
            'workspace_id' => \Yii::t('app', 'Workspace'),
            'uuid' => \Yii::t('app', 'UUID'),
            'deleted' => \Yii::t('app', 'Deleted'),
            'deactivated' => \Yii::t('app', 'Deactivated'),
        ];
    }
}
