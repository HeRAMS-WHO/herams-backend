<?php
declare(strict_types=1);

namespace prime\widgets\menu;

use prime\interfaces\WorkspaceForTabMenu;
use prime\models\ar\Permission;

class WorkspaceTabMenu extends TabMenu
{
    public WorkspaceForTabMenu $workspace;

    public function init(): void
    {
        parent::init();
        $this->permissionSubject = $this->workspace;
    }

    protected function renderMenu(): string
    {
        $this->tabs = [];

        $this->tabs[] = [
            'permission' => Permission::PERMISSION_LIST_FACILITIES,
            'url' => ["workspace/facilities", 'id' => $this->workspace->id()],
            'title' => \Yii::t('app', 'Health Facilities') . " ({$this->workspace->getFacilityCount()})"
        ];
        $this->tabs[] = [
            'permission' => Permission::PERMISSION_ADMIN,
            'url' => ["workspace/update", 'id' => $this->workspace->id()],
            'title' => \Yii::t('app', 'Workspace settings')
        ];
        $this->tabs[] = [
            'url' => ['workspace/share', 'id' => $this->workspace->id()],
            'title' => \Yii::t('app', 'Users ({n})', ['n' => $this->workspace->getPermissionSourceCount()]),
            'permission' => Permission::PERMISSION_SHARE
        ];
        $this->tabs[] = [
            'visible' => function () {
                // The permission check in visible is added since we only want to show the tab for global admins.
                // The permission key uses the project as a target
                return \Yii::$app->user->can(Permission::PERMISSION_ADMIN) && $this->workspace->getResponseCount() > 0;
            },
            'url' => ['workspace/responses', 'id' => $this->workspace->id()],
            'title' => \Yii::t('app', 'Responses')
        ];
        $this->tabs[] = [
            'title' => \Yii::t('app', 'Export data'),
            'url' => ['workspace/export', 'id' => $this->workspace->id()],
            'permission' => Permission::PERMISSION_EXPORT
        ];
        return parent::renderMenu();
    }
}
