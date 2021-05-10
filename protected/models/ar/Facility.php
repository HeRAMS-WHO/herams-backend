<?php
declare(strict_types=1);

namespace prime\models\ar;

use prime\components\ActiveQuery;
use prime\models\ActiveRecord;
use prime\queries\FacilityQuery;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @property int $id
 * @property string $uuid
 * @property string $name
 * @property array $i18n
 * @property string $alternative_name
 * @property string $code
 * @property null|DateTime $deleted
 * @property null|DateTime $deactivated
 *
 */
class Facility extends ActiveRecord
{
    public static function find()
    {
        return new FacilityQuery(static::class);
    }
    public function __construct($config = [])
    {
        parent::__construct($config);
        if (!isset($this->uuid)) {
            $this->uuid = Uuid::uuid6()->getBytes();
        }
    }

    public function attributeLabels(): array
    {
        return parent::attributeLabels() + [
            'name' => \Yii::t('app', 'Name'),
            'alternative_name' => \Yii::t('app', 'Alternative name')
        ];
    }
}
