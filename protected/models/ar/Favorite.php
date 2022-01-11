<?php
declare(strict_types=1);

namespace prime\models\ar;

use prime\models\ActiveRecord;
use prime\queries\FavoriteQuery;
use yii\db\ActiveQuery;
use yii\validators\ExistValidator;
use yii\validators\RangeValidator;

/**
 * Class Favorite
 * @package prime\models\ar
 * @property int $user_id
 * @property string $target_class
 * @property int $target_id
 */
class Favorite extends ActiveRecord
{
    public static function find(): FavoriteQuery
    {
        return new FavoriteQuery(self::class);
    }

    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id'])->inverseOf('favorites');
    }

    public function getTarget()
    {
        if (!in_array($this->target_class, [
            Project::class,
            Workspace::class
        ])) {
            throw new \RuntimeException('Unknown favorite type: ' . $this->target_class);
        }
        return $this->target_class::findOne(['id' => $this->target_id]);
    }

    public function matches(ActiveRecord $target): bool
    {
        return $this->target_class === get_class($target) && $this->target_id === (int) $target->getAttribute('id');
    }


    public function rules()
    {
        return [
            [['target_class'], RangeValidator::class, 'range' => [Workspace::class]],
            [['user_id'], ExistValidator::class, 'targetRelation' => 'user'],
            [['target_id'], ExistValidator::class, 'targetAttribute' => 'id', 'targetClass' => Workspace::class, 'when' => static function (Favorite $model) {
                return $model->target_class === Workspace::class;
            }]
        ];
    }
}
