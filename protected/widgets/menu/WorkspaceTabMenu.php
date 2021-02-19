<?php
declare(strict_types=1);

namespace prime\widgets\menu;

use prime\models\ar\Permission;
use prime\models\ar\Workspace;

/**
 * Class Menu
 * Implements a tab menu for admin project pages
 * @package prime\widgets\menu
 */
class WorkspaceTabMenu extends TabMenu
{
    public Workspace $workspace;

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
            'url' => ["workspace/limesurvey", 'id' => $this->workspace->id],
            'title' => \Yii::t('app', 'Health Facilities') . " ({$this->workspace->facilityCount})"
        ];
        $this->tabs[] = [
            'permission' => Permission::PERMISSION_ADMIN,
            'url' => ["workspace/update", 'id' => $this->workspace->id],
            'title' => \Yii::t('app', 'Workspace settings')
        ];
        $this->tabs[] = [
            'url' => ['workspace/share', 'id' => $this->workspace->id],
            'title' => \Yii::t('app', 'Users ({n})', ['n' => $this->workspace->permissionSourceCount]),
            'permission' => Permission::PERMISSION_SHARE
        ];
        $this->tabs[] = [
            'visible' => function () {
                // The permission check in visible is added since we only want to show the tab for global admins.
                // The permission key uses the project as a target
                return \Yii::$app->user->can(Permission::PERMISSION_ADMIN) && $this->workspace->responseCount > 0;
            },
            'url' => ['workspace/responses', 'id' => $this->workspace->id],
            'title' => \Yii::t('app', 'Responses')
        ];
        $this->tabs[] = [
            'title' => \Yii::t('app', 'Export data'),
            'url' => ['workspace/export', 'id' => $this->workspace->id],
            'permission' => Permission::PERMISSION_EXPORT
        ];

        return parent::renderMenu();
    }
}
