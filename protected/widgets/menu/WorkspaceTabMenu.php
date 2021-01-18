<?php


namespace prime\widgets\menu;

use yii\base\Widget;
use prime\interfaces\PageInterface;
use prime\models\ar\Permission;
use yii\helpers\Html;
use \prime\models\ar\Workspace;

/**
 * Class Menu
 * Implements a tab menu for admin project pages
 * @package prime\widgets\menu
 */
class WorkspaceTabMenu extends TabMenu
{
    /** @var \prime\models\ar\Workspace */
    public Workspace $workspace;

    public function init()
    {
        parent::init();
        $this->permissionSubject = $this->workspace;
    }


    protected function renderMenu(): string
    {
        $this->tabs = [];

        $this->tabs[] = [
            'permission' => Permission::PERMISSION_SURVEY_DATA,
            'url' => ["workspace/limesurvey", 'id' => $this->workspace->id],
            'title' => \Yii::t('app', 'Health Facilities') . " ({$this->workspace->facilityCount})"
        ];
        $this->tabs[] = [
            'permission' => Permission::PERMISSION_ADMIN,
            'url' => ["workspace/update", 'id' => $this->workspace->id],
            'title' => \Yii::t('app', 'Workspace settings')
        ];

        $this->tabs[] =[
            'permission' => Permission::PERMISSION_SHARE,
            'url' => ["workspace/share", 'id' => $this->workspace->id],
            'title' => \Yii::t('app', 'Users')
        ];

        $this->tabs[] = [
            'visible' => function () {
                return $this->workspace->responseCount > 0;
            },
            'permission' => Permission::PERMISSION_ADMIN,
            'url' => ['workspace/responses', 'id' => $this->workspace->id],
            'title' => \Yii::t('app', 'Responses')
        ];

        $this->tabs[] = [
            'title' => \Yii::t('app', 'Download'),
            'url' => ['workspace/export', 'id' => $this->workspace->id],
            'permission' => Permission::PERMISSION_EXPORT
        ];


        return parent::renderMenu();
    }
}
