<?php

namespace prime\models;

use prime\components\ActiveRecord;
use prime\models\permissions\Permission;
use prime\objects\ResponseCollection;

/**
 * Class Project
 * @package prime\models
 *
 * @property User $user
 * @property Tool $tool
 * @property string $title
 * @property string $description
 */
class Project extends ActiveRecord {

    public static function find()
    {
        $query = parent::find();
        //if the logged in user is admin, access to all projects is allowed
        if(!app()->user->identity->isAdmin) {
            //Select all project ids where the logged in user is owner of
            $ids = parent::find()->andWhere(['owner_id' => app()->user->id])->select('id')->column();
            //Select all project ids where the logged in user is invited to
            $ids2 = Permission::find()
                ->andWhere(
                    [
                        'source' => User::class,
                        'source_id' => app()->user->id,
                        'target' => Project::class,
                    ]
                )
                ->select('target_id');
            $query->andWhere([
                'or',
                ['id' => $ids],
                ['id' => $ids2]
            ]);
        }
        return $query;
    }

    public function getOwner()
    {
        return $this->hasOne(User::class, ['id' => 'owner_id'])
            ->inverseOf('projects');
    }

    /**
     * @return Widget
     */
    public function getProgressWidget()
    {
        $widget = $this->tool->progressWidget;
        $widget->project = $this;
        return $widget;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReports()
    {
        return $this->hasMany(Report::class, ['project_id' => 'id']);
    }

    /**
     * @return ResponseCollection
     */
    public function getResponses()
    {
        return new ResponseCollection(['allModels' => []]);
    }

    public function getTool()
    {
        return $this->hasOne(Tool::class, ['id' => 'tool_id']);
    }

    /**
     * @param $reportGenerator
     * @return $this
     */
    public function getUserData($reportGenerator)
    {
        return $this->hasOne(UserData::class, ['project_id' => 'id'])
            ->andWhere(['generator' => $reportGenerator]);
    }

    public function rules()
    {
        return [
            [['title', 'description', 'owner_id', 'data_survey_eid', 'tool_id'], 'required'],
            [['title', 'description'], 'string'],
            [['owner_id', 'data_survey_id', 'tool_id'], 'integer'],
            [['owner_id'], 'exist', 'targetClass' => User::class, 'targetAttribute' => 'id'],
            [['tool_id'], 'exist', 'targetClass' => Tool::class, 'targetAttribute' => 'id']
        ];
    }

    public function scenarios()
    {
        return [
            'create' => ['title', 'description', 'owner_id', 'data_survey_eid', 'tool_id'],
            'update' => ['title', 'description']
        ];
    }

    /**
     * Shares this project with user, returns whether the project sharing was successfull
     * @param User $user
     * @param string $permission
     * @return bool
     * @throws \Exception
     */
    public function shareWith(User $user, $permission = Permission::PERMISSION_READ)
    {
        if($user->id != $this->owner_id)
            return true;

        return !Permission::grand($this, $user, $permission)->isNewRecord;

    }

    public function userCan($operation, User $user = null)
    {
        $user = (isset($user)) ? (($user instanceof User) ? $user : User::findOne($user)) : app()->user->identity;

        $result = parent::userCan($operation, $user);
        if(!$result) {
            $result = $result || $this->owner_id == $user->id;
            $result = $result || Permission::isAllowed($user, $this, $operation);
        }
        return $result;
    }


}