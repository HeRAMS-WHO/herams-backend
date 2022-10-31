<?php

declare(strict_types=1);

namespace prime\models\ar\read;

use Collecthor\DataInterfaces\RecordInterface;
use prime\behaviors\LocalizableReadBehavior;
use prime\helpers\ArrayHelper;
use prime\models\ar\SurveyResponse;
use prime\traits\ReadOnlyTrait;
use SamIT\Yii2\VirtualFields\VirtualFieldBehavior;
use yii\db\Expression;

class Facility extends \prime\models\ar\Facility implements RecordInterface
{
    use ReadOnlyTrait;

    public function behaviors(): array
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                VirtualFieldBehavior::class => [
                    'class' => VirtualFieldBehavior::class,
                    'virtualFields' => [
                        'responseCount' => [
                            VirtualFieldBehavior::CAST => VirtualFieldBehavior::CAST_INT,
                            VirtualFieldBehavior::GREEDY => SurveyResponse::find()
                                ->limit(1)->select('count(*)')
                                ->where([
                                    'facility_id' => new Expression(self::tableName() . '.[[id]]'),
                                ]),
                            VirtualFieldBehavior::LAZY => static fn (self $facility) => $facility->getDataSurveyResponses()->count(
),
                        ],
                    ],
                ],
            ]
        );
    }

    public function getDataValue(array $path): string|int|float|bool|null|array
    {
        $data = [...($this->data ?? []), ...($this->admin_data ?? [])];

        while (count($path) > 0 && is_array($data)) {
            $key = array_shift($path);
            $data = $data[$key] ?? null;
        }
        return $data;
    }

    public function allData(): array
    {
        return $this->data;
    }
}
