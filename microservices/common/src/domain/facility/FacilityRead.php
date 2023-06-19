<?php

declare(strict_types=1);

namespace herams\common\domain\facility;

use Collecthor\DataInterfaces\RecordInterface;
use Collecthor\SurveyjsParser\ArrayDataRecord;
use Collecthor\SurveyjsParser\ArrayRecord;
use herams\common\domain\survey\Survey;
use herams\common\models\ActiveRecord;
use herams\common\models\Project;
use herams\common\models\SurveyResponse;
use herams\common\models\Workspace;
use herams\common\queries\ActiveQuery;
use herams\common\queries\FacilityQuery;
use herams\common\traits\ReadOnlyTrait;
use SamIT\Yii2\VirtualFields\VirtualFieldBehavior;
use yii\db\Expression;

/**
 * @property int $id
 */
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
        return $this->data ?? [];
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
                    ])->andWhere([
                        'or',
                           ['!=', 'status', 'Deleted'],
                           ['IS', 'status', null]
                        ]),
                VirtualFieldBehavior::LAZY => static fn (self $facility) => SurveyResponse::find()
                    ->where([
                        'facility_id' => $facility->id
                    ])->andWhere([
                        'or',
                           ['!=', 'status', 'Deleted'],
                           ['IS', 'status', null]
                        ])->count(),
            ],
            'adminSurveyResponseCount' => [
                VirtualFieldBehavior::CAST => VirtualFieldBehavior::CAST_INT,
                //                VirtualFieldBehavior::GREEDY =>,
                VirtualFieldBehavior::LAZY => static fn (self $facility) => SurveyResponse::find()
                    ->where([
                        'facility_id' => $facility->id,
                        'survey_id' => Project::find()->select('admin_survey_id')
                            ->where([
                                'id' => Workspace::find()->select('project_id')->where(['id' => $facility->workspace_id])
                            ])
                    ])->andWhere([
                        'or',
                           ['!=', 'status', 'Deleted'],
                           ['IS', 'status', null]
                        ])->count(),
            ],
            'dataSurveyResponseCount' => [
                VirtualFieldBehavior::CAST => VirtualFieldBehavior::CAST_INT,
                //                VirtualFieldBehavior::GREEDY =>,
                VirtualFieldBehavior::LAZY => static fn (self $facility) => SurveyResponse::find()
                    ->where([
                        'facility_id' => $facility->id,
                        'survey_id' => Project::find()->select('data_survey_id')
                            ->where([
                                'id' => Workspace::find()->select('project_id')->where(['id' => $facility->workspace_id])
                            ])
                    ])->andWhere([
                        'or',
                           ['!=', 'status', 'Deleted'],
                           ['IS', 'status', null]
                        ])->count(),

            ],
            'date_of_update' => [
                
                VirtualFieldBehavior::LAZY => static fn (self $facility) => $facility->id ?? null,
            ],

        ];
    }
    public function canReceiveSituationUpdate(): bool
    {
        return (bool) $this->can_receive_situation_update;
    }

    public function getAdminRecord(): RecordInterface
    {
        return new ArrayDataRecord($this->admin_data ?? []);
    }

    public function getDataRecord(): RecordInterface
    {
        return new ArrayDataRecord($this->data ?? []);
    }

}
