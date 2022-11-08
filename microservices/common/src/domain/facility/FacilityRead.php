<?php

declare(strict_types=1);

namespace herams\common\domain\facility;

use Collecthor\DataInterfaces\RecordInterface;
use Collecthor\SurveyjsParser\ArrayRecord;
use herams\common\models\ActiveRecord;
use herams\common\models\SurveyResponse;
use herams\common\queries\ActiveQuery;
use herams\common\queries\FacilityQuery;
use herams\common\traits\ReadOnlyTrait;
use SamIT\Yii2\VirtualFields\VirtualFieldBehavior;
use yii\db\Expression;

final class FacilityRead extends ActiveRecord implements RecordInterface
{
    use ReadOnlyTrait;

    public static function tableName(): string
    {
        return '{{%facility}}';
    }

    public static function find(): ActiveQuery
    {
        return new FacilityQuery(self::class);
    }

    public function behaviors(): array
    {
        return [
            'virtualFields' => [
                'class' => VirtualFieldBehavior::class,
                'virtualFields' => self::virtualFields(),
            ],
        ];
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

    protected static function virtualFields()
    {
        return [
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
            'adminSurveyResponseCount' => [
                VirtualFieldBehavior::CAST => VirtualFieldBehavior::CAST_INT,
                //                VirtualFieldBehavior::GREEDY =>,
                VirtualFieldBehavior::LAZY => static function (self $model): int {
                    return -15;
                    return $model->getAdminSurveyResponses()->count();
                },
            ],
            'dataSurveyResponseCount' => [
                VirtualFieldBehavior::CAST => VirtualFieldBehavior::CAST_INT,
                //                VirtualFieldBehavior::GREEDY =>,
                VirtualFieldBehavior::LAZY => static function (self $model): int {
            return -15;
                    return $model->getDataSurveyResponses()->count();
                },
            ],

        ];
    }

    public function getDataRecord(): RecordInterface
    {
        $dt = new \DateTime();
        return new ArrayRecord($this->data ?? [], $this->id, $dt, $dt);
    }

    public function getAdminRecord(): RecordInterface
    {
        $dt = new \DateTime();
        return new ArrayRecord($this->admin_data ?? [], $this->id, $dt, $dt);
    }

    public function getDataSurveyResponses(): ActiveQuery
    {
        return $this->getSurveyResponses()->andWhere([
            'survey_id' => $this->project->data_survey_id,
        ]);
    }

    public function canReceiveSituationUpdate(): bool
    {
        return (bool) $this->can_receive_situation_update;
    }

}
