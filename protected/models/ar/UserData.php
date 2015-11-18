<?php

namespace prime\models\ar;

use Befound\ActiveRecord\Behaviors\JsonBehavior;
use prime\components\ActiveRecord;
use prime\factories\GeneratorFactory;
use prime\interfaces\UserDataInterface;
use prime\models\ar\Project;
use prime\models\ar\Tool;
use yii\validators\ExistValidator;
use yii\validators\UniqueValidator;

class UserData extends ActiveRecord implements UserDataInterface
{
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                JsonBehavior::class => [
                    'class' => JsonBehavior::class,
                    'jsonAttributes' => ['data']
                ]
            ]
        );
    }

    public function getData()
    {
        return $this->data->asArray();
    }

    public function getProject()
    {
        return $this->hasOne(Project::class, ['id' => 'project_id']);
    }

    public function rules()
    {
        return [
            [['data'], 'safe'],
            [['project_id', 'generator'], 'required'],
            [['project_id'], ExistValidator::class, 'targetClass' => Project::class, 'targetAttribute' => 'id'],
            [['generator'], 'in', 'range' => array_keys(GeneratorFactory::classes())],
            [['project_id', 'generator'], UniqueValidator::class, 'targetAttribute' => ['project_id', 'generator']]
        ];
    }
}