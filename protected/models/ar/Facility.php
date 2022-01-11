<?php
declare(strict_types=1);

namespace prime\models\ar;

use prime\components\ActiveQuery;
use prime\models\ActiveRecord;
use prime\queries\FacilityQuery;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @property string $id
 * @property-read UuidInterface $uuid
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
        if (!isset($this->id)) {
            $this->id = Uuid::uuid6()->getBytes();
        }
    }

    public function getUuid(): UuidInterface
    {
        return Uuid::fromBytes($this->id);
    }

    public function attributeLabels(): array
    {
        return parent::attributeLabels() + [
            'name' => \Yii::t('app', 'Name'),
            'alternative_name' => \Yii::t('app', 'Alternative name')
        ];
    }
}
