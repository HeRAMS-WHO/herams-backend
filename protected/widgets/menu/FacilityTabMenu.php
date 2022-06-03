<?php

declare(strict_types=1);

namespace prime\widgets\menu;

use prime\interfaces\FacilityForTabMenu;
use prime\models\ar\Permission;

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
            'permission' => Permission::PERMISSION_LIST_DATA_RESPONSES,
            'title' => \Yii::t('app', 'Responses ({n})', [
                'n' => $this->facility->getResponseCount(),
            ]),
            'url' => [
                'facility/responses',
                'id' => $this->facility->getId(),

            ],
        ];
        $this->tabs[] = [
            'permission' => Permission::PERMISSION_LIST_ADMIN_RESPONSES,
            'title' => \Yii::t('app', 'Admin responses ({n})', [
                'n' => $this->facility->getAdminResponseCount(),
            ]),
            'url' => [
                'facility/admin-responses',
                'id' => $this->facility->getId(),

            ],
        ];
        $this->tabs[] = [
            'permission' => Permission::PERMISSION_WRITE,
            'title' => \Yii::t('app', 'Facility settings'),
            'url' => [
                "facility/update",
                'id' => $this->facility->getId(),
            ],
            'visibility' => is_numeric($this->facility->getId()->getValue()),
        ];

        return parent::renderMenu();
    }
}
