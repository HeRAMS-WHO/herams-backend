<?php


namespace prime\widgets\menu;

use prime\helpers\Icon;
use prime\interfaces\PageInterface;
use prime\models\ar\Page;
use yii\helpers\Html;

/**
 * Class Menu
 * Implements a side menu for workspace pages
 * @package prime\widgets\menu
 */
class WorkspacePageMenu extends SideMenu
{
    /** @var Workspace */
    public $workspace;

    /** @var PageInterface */
    public $currentPage;

    public $params = [];

    public function init()
    {
        $this->title = $this->workspace->project->title;
        parent::init();
    }


    protected function renderMenu()
    {
        echo Html::tag('h3', $this->workspace->title);
        $actions = [['action' => 'view', 'title' => 'Data'], ['action' => 'share', 'title' => 'Sharing'], ['action' => 'update', 'title' => 'Settings']];

        foreach ($actions as $action) {
            $options = [];
            if ($this->currentPage == $action['action']) {
                Html::addCssClass($options, 'active');
            }

            echo Html::a($action['title'], ["workspace/{$action['action']}", 'id' => $this->workspace->id], $options);
        }
    }
}
