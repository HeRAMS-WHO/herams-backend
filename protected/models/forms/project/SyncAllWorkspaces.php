<?php
declare(strict_types=1);

namespace prime\models\forms\project;

use Carbon\Carbon;
use prime\models\ar\Project;
use prime\models\ar\Workspace;
use yii\base\Model;
use yii\helpers\Html;
use yii\validators\ExistValidator;
use yii\validators\RangeValidator;
use yii\validators\RequiredValidator;
use function iter\keys;
use function iter\toArray;

class SyncAllWorkspaces extends Model
{

    private Project $project;
    public array $workspaces = [];


    public function __construct(Project $project, $config = [])
    {
        parent::__construct($config);
        $this->project = $project;
    }

    public function workspaceOptions(): iterable
    {
        yield from $this->project->workspaces;
    }

    public function attributeHints(): array
    {
        return [];
    }

    public function getSelectedWorkspaces(): iterable
    {
        return $this->project->getWorkspaces()->each();
    }

    public function rules(): array
    {
        return [];
    }
}
