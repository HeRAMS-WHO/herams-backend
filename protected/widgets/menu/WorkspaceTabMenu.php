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
    public $workspace;

    public function init()
    {
        parent::init();
    }


    protected function renderMenu()
    {
        $this->tabs = [];

        if (\Yii::$app->user->can(Permission::PERMISSION_SURVEY_DATA, $this->workspace)) {
            $this->tabs[] =
                [
                    'url' => ["workspace/limesurvey", 'id' => $this->workspace->id],
                    'title' => \Yii::t('app', 'Health Facilities') . " ({$this->workspace->facilityCount})"
                ];
        }
        if (\Yii::$app->user->can(Permission::PERMISSION_ADMIN, $this->workspace)) {
            $this->tabs[] =
                [
                    'url' => ["workspace/update", 'id' => $this->workspace->id],
                    'title' => \Yii::t('app', 'Workspace settings')
                ];
        }
        if (\Yii::$app->user->can(Permission::PERMISSION_SHARE, $this->workspace)) {
            $this->tabs[] =
                [
                    'url' => ["workspace/share", 'id' => $this->workspace->id],
                    'title' => \Yii::t('app', 'Users')
                ];
        }

        if ($this->workspace->responseCount > 0 && \Yii::$app->user->can(Permission::PERMISSION_ADMIN, $this->workspace)) {
            $this->tabs[] =
                [
                    'url' => ['workspace/responses', 'id' => $this->workspace->id],
                    'title' => \Yii::t('app', 'Responses')
                ];
        }

        parent::renderMenu();
    }

    public function run()
    {
        $this->renderMenu();
    }
}
