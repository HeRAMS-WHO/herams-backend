<?php

declare(strict_types=1);

namespace prime\models\forms;

use prime\models\ar\Project;
use prime\models\ar\WorkspaceForLimesurvey as WorkspaceModel;
use prime\values\ProjectId;
use yii\base\Model;
use yii\db\Query;
use yii\validators\ExistValidator;
use yii\validators\RequiredValidator;
use yii\validators\StringValidator;
use yii\validators\UniqueValidator;

class Workspace extends Model
{
    public null|string $title = null;

    public null|string $token = null;

    public function __construct(public ProjectId $projectId)
    {
        parent::__construct();
    }

    public function rules(): array
    {
        return [
            [['title'], RequiredValidator::class],
            [['title'],
                StringValidator::class,
                'min' => 1,
            ],
            [['!projectId'],
                ExistValidator::class,
                'targetClass' => Project::class,
                'targetAttribute' => 'id',
            ],
            [['token'],
                UniqueValidator::class,
                'targetClass' => WorkspaceModel::class,
                'filter' => function (Query $query) {
                    $query->andWhere([
                        'project_id' => $this->projectId,
                    ]);
                },
            ],
        ];
    }
}
