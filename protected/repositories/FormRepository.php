<?php

declare(strict_types=1);

namespace prime\repositories;

use herams\common\values\ProjectId;
use herams\common\values\WorkspaceId;
use prime\interfaces\SurveyFormInterface;
use prime\models\forms\SurveyForm;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;
use yii\helpers\Url;

/**
 * A repository for platform forms. Forms are SurveyJS surveys that are stored as code.
 * They are used for platform operations like creating a new project.
 */
class FormRepository
{
    private function loadDefinition(string $name): array
    {
        return json_decode(file_get_contents(\Yii::getAlias("@app/models/forms/$name.json")), true, 512, JSON_THROW_ON_ERROR);
    }

    private function createUri(string $route, ...$params): UriInterface
    {
        return $this->uriFactory->createUri(Url::toRoute([$route, ...$params]));
    }

    public function __construct(
        private UriFactoryInterface $uriFactory
    ) {
    }

    public function getCreateProjectForm(): SurveyFormInterface
    {
        return new SurveyForm(
            submitRoute: $this->createUri('/api/project/create'),
            redirectRoute: $this->createUri('project/index'),
            serverValidationRoute: $this->createUri('/api/project/validate'),
            configuration: $this->loadDefinition('createUpdateProject'),
        );
    }

    public function getUpdateProjectForm(ProjectId $id): SurveyFormInterface
    {
        return new SurveyForm(
            submitRoute: $this->createUri('/api/project/update', id: $id),
            dataRoute: $this->createUri('/api/project/view', id: $id),
            serverValidationRoute: $this->createUri('/api/project/validate', id: $id),
            configuration: $this->loadDefinition('createUpdateProject'),
            extraData: [
                'id' => $id,
            ]
        );
    }

    public function getCreateWorkspaceForm(ProjectId $id): SurveyFormInterface
    {
        return new SurveyForm(
            submitRoute: $this->createUri('/api/workspace/create', id: $id),
            redirectRoute: $this->createUri('/project/workspaces', id: $id),
            serverValidationRoute: $this->createUri('/api/workspace/validate'),
            configuration: $this->loadDefinition('createUpdateWorkspace'),
            localeEndpoint: $this->createUri('/api/project/view', id: $id),
            extraData: [
                'projectId' => $id,
            ]
        );
    }

    public function getUpdateWorkspaceForm(WorkspaceId $id): SurveyFormInterface
    {
        return new SurveyForm(
            submitRoute: $this->createUri('/api/workspace/update', id: $id),
            dataRoute: $this->createUri('/api/workspace/view', id: $id),
            serverValidationRoute: $this->createUri('/api/workspace/validate', id: $id),
            configuration: $this->loadDefinition('createUpdateWorkspace'),
            //            localeEndpoint: $this->createUri('/api/project/view', id: $id),
            extraData: [
                'id' => $id,
            ]
        );
    }
}
