<?php

declare(strict_types=1);

namespace prime\models\forms\project;

use prime\models\ar\Project;
use yii\base\Model;
use yii\validators\RequiredValidator;

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
        yield from $this->project->workspaces;
    }

    public function attributeHints(): array
    {
        return [];
    }

    public function getSelectedWorkspaces(): iterable
    {
        return $this->project->getWorkspaces()->andWhere([
            'id' => $this->workspaces,
        ])->each();
    }

    public function rules(): array
    {
        return [
            [['workspaces'], RequiredValidator::class],
        ];
    }
}
