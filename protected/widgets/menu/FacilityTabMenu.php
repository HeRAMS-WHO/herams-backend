<?php

declare(strict_types=1);

namespace prime\widgets\menu;

use herams\common\models\Permission;
use prime\interfaces\FacilityForTabMenu;

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

        // $this->tabs[] = [
        //     'permission' => Permission::PERMISSION_READ,
        //     'title' => \Yii::t('app', 'Current situation'),
        //     'url' => [
        //         'facility/view',
        //         'id' => $this->facility->getId(),

        //     ],
        // ];

        $this->tabs[] = [
            'permission' => Permission::PERMISSION_LIST_DATA_RESPONSES,
            'title' => \Yii::t('app', 'Situation Updates ({n})', [
                'n' => $this->facility->getResponseCount(),
            ]),
            'url' => [
                'facility/responses',
                'id' => $this->facility->getId(),

            ],
        ];
        $this->tabs[] = [
            'permission' => Permission::PERMISSION_LIST_ADMIN_RESPONSES,
            'title' => \Yii::t('app', 'HSDU updates ({n})', [
                'n' => $this->facility->getAdminResponseCount(),
            ]),
            'url' => [
                'facility/admin-responses',
                'id' => $this->facility->getId(),

            ],
        ];
        $this->tabs[] = [
            'permission' => Permission::PERMISSION_WRITE,
            'title' => \Yii::t('app', 'Delete HSDU'),
            'url' => [
                "facility/update",
                'id' => $this->facility->getId(),
            ],
            'visibility' => is_numeric($this->facility->getId()->getValue()),
        ];

        return parent::renderMenu();
    }
}
