<?php
declare(strict_types=1);

namespace prime\widgets\menu;

use prime\interfaces\FacilityForTabMenu;
use prime\models\ar\Permission;
use prime\models\ar\Workspace;

class FacilityTabMenu extends TabMenu
{
    public FacilityForTabMenu $facility;

    public function init(): void
    {
        parent::init();
        $this->permissionSubject = $this->facility;
    }

    protected function renderMenu(): string
    {
        $this->tabs = [];

        $this->tabs[] = [
            'url' => ['facility/responses', 'id' => $this->facility->getId()],
            'title' => \Yii::t('app', 'Responses ({n})', ['n' => $this->facility->getResponseCount()])
        ];
        $this->tabs[] = [
            'url' => ['facility/admin-responses', 'id' => $this->facility->getId()],
            'title' => \Yii::t('app', 'Admin responses ({n})', ['n' => $this->facility->getResponseCount()]),
            'permission' => Permission::PERMISSION_ADMIN
        ];
        $this->tabs[] = [
            'permission' => Permission::PERMISSION_ADMIN,
            'url' => ["facility/update", 'id' => $this->facility->getId()],
            'title' => \Yii::t('app', 'Facility settings'),
            'visibility' => is_numeric($this->facility->getId()->getValue())
        ];

        return parent::renderMenu();
    }
}
