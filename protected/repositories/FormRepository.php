<?php

declare(strict_types=1);

namespace prime\repositories;

use herams\common\domain\project\ProjectRepository;
use herams\common\domain\workspace\WorkspaceRepository;
use herams\common\models\Workspace;
use herams\common\values\ProjectId;
use herams\common\values\WorkspaceId;
use prime\helpers\SurveyConfiguration;
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
    public function __construct(
        private UriFactoryInterface $uriFactory,
        private ProjectRepository $projectRepository,
        private WorkspaceRepository $workspaceRepository
    ) {
    }

    private function loadDefinition(string $name): array
    {
        return json_decode(file_get_contents(\Yii::getAlias("@app/models/forms/$name.json")), true, 512, JSON_THROW_ON_ERROR);
    }

    private function createUri(string $route, ...$params): UriInterface
    {
        return $this->uriFactory->createUri(Url::toRoute([$route, ...$params]));
    }

    public function getCreateProjectForm(): SurveyFormInterface
    {
        return new SurveyForm(
            submitRoute: $this->createUri('/api/project/create'),
            redirectRoute: $this->createUri('project/index'),
            serverValidationRoute: $this->createUri('/api/project/validate'),
            configuration: SurveyConfiguration::forCreatingProject()
        );
    }

    public function getUpdateProjectForm(ProjectId $id): SurveyFormInterface
    {
        $project = $this->projectRepository->retrieveById($id);
        return new SurveyForm(
            submitRoute: $this->createUri('/api/project/update', id: $id),
            dataRoute: $this->createUri('/api/project/view', id: $id),
            serverValidationRoute: $this->createUri('/api/project/validate', id: $id),
            configuration: SurveyConfiguration::forUpdatingProject($project),
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

    public function getUpdateWorkspaceForm(WorkspaceId $id, ProjectId $projectId = null): SurveyFormInterface
    {
        $localization = Workspace::find(['id' => $id->getValue()])->one()->toArray()['i18n']['title'];
        return new SurveyForm(
            submitRoute: $this->createUri('/api/workspace/update', id: $id),
            serverValidationRoute: $this->createUri('/api/workspace/validate', id: $id),
            configuration: SurveyConfiguration::forUpdatingWorkspace($localization),
            localeEndpoint: isset($projectId) ? $this->createUri('/api/project/view', id: $projectId) : null,
            extraData: [
                'id' => $id,
            ]
        );
    }
}
