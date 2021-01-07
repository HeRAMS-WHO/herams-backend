<?php


namespace prime\widgets\menu;

use yii\base\Widget;
use prime\interfaces\PageInterface;
use prime\models\ar\Permission;
use yii\helpers\Html;
use \prime\models\ar\Project;

/**
 * Class Menu
 * Implements a tab menu for admin project pages
 * @package prime\widgets\menu
 */
class ProjectTabMenu extends TabMenu
{
    /** @var \prime\models\ar\Project */
    public $project;

    public function init()
    {
        parent::init();
    }


    protected function renderMenu()
    {
        $this->tabs = [
            [
                'url' => ['project/workspaces', 'id' => $this->project->id],
                'title' => \Yii::t('app', 'Workspaces') . " ({$this->project->workspaceCount})"
            ]
        ];

        if (\Yii::$app->user->can(Permission::PERMISSION_MANAGE_DASHBOARD, $this->project)) {
            $this->tabs[] =
                [
                    'url' => ['project/pages', 'id' => $this->project->id],
                    'title' => \Yii::t('app', 'Dashboard settings')
                ];
        }
        if (\Yii::$app->user->can(Permission::PERMISSION_WRITE, $this->project)) {
            $this->tabs[] =
                [
                    'url' => ['project/update', 'id' => $this->project->id],
                    'title' => \Yii::t('app', 'Project settings')
                ];
        }
        if (\Yii::$app->user->can(Permission::PERMISSION_SHARE, $this->project)) {
            $this->tabs[] =
                [
                    'url' => ['project/share', 'id' => $this->project->id],
                    'title' => \Yii::t('app', 'Users') . " ({$this->project->contributorCount})"
                ];
        }
        if (\Yii::$app->user->can(Permission::PERMISSION_SURVEY_BACKEND, $this->project)) {
            $this->tabs[] =
                [
                    'url' => ['/admin/limesurvey'],
                    'title' => \Yii::t('app', 'Backend administration')
                ];
        }

        parent::renderMenu();
    }

    public function run()
    {
        $this->renderMenu();
    }
}
