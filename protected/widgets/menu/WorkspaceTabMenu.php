<?php

declare(strict_types=1);

namespace prime\widgets\menu;

use herams\common\models\PermissionOld;
use prime\helpers\Icon;
use prime\interfaces\WorkspaceForTabMenu;
use Yii;

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
            'permission' => PermissionOld::PERMISSION_LIST_FACILITIES,
            'url' => [
                "workspace/facilities",
                'id' => $this->workspace->id(),
            ],
            'title' => Yii::t('app', 'HSDUs') . " ({$this->workspace->getFacilityCount()})
",
        ];
        $this->tabs[] = [
            'permission' => PermissionOld::PERMISSION_ADMIN,
            'url' => [
                "workspace/update",
                'id' => $this->workspace->id(),
            ],
            'title' => Yii::t(
                'app',
                'Workspace settings'
            ),
        ];
        $this->tabs[] = [
            'url' => [
                'workspace/users',
                'id' => $this->workspace->id(),
            ],
            'title' => Yii::t('app', 'Users ({n})', [
                'n' => $this->workspace->getPermissionSourceCount(),
            ]),
            'permission' =>
                PermissionOld::PERMISSION_SHARE,
        ];
        $this->tabs[] = [
            'title' => Icon::broken() . Yii::t('app', 'Export data'),
            'url' => [
                'workspace/export',
                'id' => $this->workspace->id(),
            ],
            'permission' => PermissionOld::
            PERMISSION_EXPORT,
        ];
        return parent::renderMenu();
    }
}
