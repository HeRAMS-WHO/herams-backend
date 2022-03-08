<?php

declare(strict_types=1);

namespace prime\widgets\menu;

use prime\interfaces\project\ProjectForTabMenuInterface;
use prime\models\ar\Permission;

/**
 * Class Menu
 * Implements a tab menu for admin project pages
 * @package prime\widgets\menu
 */
class ProjectTabMenu extends TabMenu
{
    public ProjectForTabMenuInterface $project;

    public function init(): void
    {
        parent::init();
        $this->permissionSubject = $this->project;
    }

    protected function renderMenu(): string
    {
        $projectId = $this->project->getId();
        $this->tabs[] = [
            'url' => ['project/workspaces', 'id' => $projectId],
            'title' => \Yii::t('app', 'Workspaces ({n})', ['n' => $this->project->getWorkspaceCount()])
        ];
        $this->tabs[] = [
            'permission' => Permission::PERMISSION_EXPORT,
            'url' => ['project/export', 'id' => $projectId],
            'title' => \Yii::t('app', 'Export data')
        ];
        $this->tabs[] = [
            'url' => ['project/pages', 'id' => $projectId],
            'permission' => Permission::PERMISSION_MANAGE_DASHBOARD,
            'title' => \Yii::t('app', 'Dashboard settings'),
            'active' => \Yii::$app->requestedRoute === 'project/import-dashboard',
        ];
        $this->tabs[] = [
            'url' => ['project/update', 'id' => $projectId],
            'permission' => Permission::PERMISSION_WRITE,
            'title' => \Yii::t('app', 'Project settings')
        ];
        $this->tabs[] = [
            'url' => ['project/share', 'id' => $projectId],
            'title' => \Yii::t('app', 'Users ({n})', ['n' => $this->project->getPermissionSourceCount()]),
            'permission' => Permission::PERMISSION_SHARE
        ];
        $this->tabs[] = [
            'permission' => Permission::PERMISSION_SURVEY_BACKEND,
            'url' => ['project/limesurvey', 'id' => $projectId],
            'title' => \Yii::t('app', 'Backend administration')
        ];

        return parent::renderMenu();
    }
}
