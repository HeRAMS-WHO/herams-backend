<?php
declare(strict_types=1);

namespace prime\models\forms\project;

use Carbon\Carbon;
use prime\models\ar\Project;
use prime\models\ar\Workspace;
use prime\objects\BatchResult;
use yii\base\Model;
use yii\helpers\Html;
use yii\validators\RangeValidator;
use yii\validators\RequiredValidator;
use function iter\keys;
use function iter\toArray;

class SyncWorkspaces extends Model
{

    private Project $project;

    public array $workspaces = [];


    public function __construct(
        Project $project,
        $config = []
    ) {
        parent::__construct($config);
        $this->project = $project;
    }

    public function workspaceOptions(): iterable
    {
        foreach ($this->project->workspaces as $workspace) {
            $title        = Html::tag('span', $workspace->title);
            $latestUpdate = Html::tag(
                'time-ago',
                ($workspace->latestUpdate ?? \Yii::t('app', 'never')),
                [
                    'datetime' => $workspace->latestUpdate ? (new Carbon($workspace->latestUpdate))->toIso8601String() : \Yii::t('app', 'never'),
                ]
            );
            yield $workspace->id => "$title$latestUpdate";
        }
    }

    public function attributeHints(): array
    {
        return [];
    }

    public function getSelectedWorkspaces(): iterable
    {
        return Workspace::find()->andWhere(['id' => $this->workspaces])->each();
    }
    public function rules(): array
    {
        return [
            [['workspaces'], RequiredValidator::class],
            [['workspaces'], RangeValidator::class, 'range' => toArray(keys($this->workspaceOptions())), 'allowArray' => true],
        ];
    }
}
