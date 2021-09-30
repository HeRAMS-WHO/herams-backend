<?php
declare(strict_types=1);

namespace prime\models\ar;

use prime\components\ActiveQuery as ActiveQuery;
use prime\helpers\ArrayHelper;
use prime\models\ActiveRecord;
use prime\queries\FacilityQuery;
use prime\queries\ResponseQuery;
use yii\validators\ExistValidator;
use yii\validators\RequiredValidator;
use yii\validators\StringValidator;

/**
 * Attributes
 * @property string $created
 * @property int $id
 * @property string $title
 * @property string $token
 * @property int $tool_id
 *
 * Virtual attributes
 * @property int $contributorCount
 * @property int $facilityCount
 * @property ?string $latestUpdate
 * @property int $permissionSourceCount
 * @property int $responseCount
 *
 * Relations
 * @property-read User $owner
 * @property-read Project $project
 */
class Workspace extends ActiveRecord
{
    public function behaviors(): array
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [

            ]
        );
    }

    public function getFacilities(): FacilityQuery
    {
        return $this->hasMany(Facility::class, ['workspace_id' => 'id']);
    }

    /**
     * @return User[]
     */
    public function getLeads(): array
    {
        $permissionQuery = Permission::find()->andWhere([
            'target' => self::class,
            'target_id' => $this->id,
            'source' => User::class,
            'permission' => Permission::ROLE_LEAD
        ]);

        $result = User::find()->andWhere(['id' => $permissionQuery->select('source_id')])->all();

        return !empty($result) ? $result : $this->project->getLeads();
    }

    public function getPermissions(): ActiveQuery
    {
        return $this->hasMany(Permission::class, ['target_id' => 'id'])
            ->andWhere(['target' => self::class]);
    }

    public function getProject(): ActiveQuery
    {
        return $this->hasOne(Project::class, ['id' => 'tool_id'])->inverseOf('workspaces');
    }

    public function getResponses(): ResponseQuery
    {
        return $this->hasMany(Response::class, ['workspace_id' => 'id'])->inverseOf('workspace');
    }

    public static function instantiate($row): ActiveRecord
    {
        // Single table inheritance: when we need a WorkspaceForLimesurvey instance,
        if (!empty($row['token'])) {
            return new WorkspaceForLimesurvey();
        }

        return parent::instantiate($row);
    }

    public static function labels(): array
    {
        return array_merge(parent::labels(), [
            'project.title' => \Yii::t('app.model.workspace', 'Project'),
            'tool_id' => \Yii::t('app.model.workspace', 'Project'),
        ]);
    }

    public function rules(): array
    {
        return [
            [['title', 'tool_id'], RequiredValidator::class],
            [['title'], StringValidator::class, 'min' => 1],
            [['tool_id'], ExistValidator::class, 'targetRelation' => 'project'],
        ];
    }

    public function scenarios(): array
    {
        $result = parent::scenarios();
        $result[self::SCENARIO_DEFAULT][] = '!tool_id';
        return $result;
    }

    public static function tableName(): string
    {
        return '{{%workspace}}';
    }
}
