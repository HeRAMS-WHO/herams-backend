<?php

namespace prime\widgets\menu;

use prime\helpers\Icon;
use prime\interfaces\PageInterface;
use prime\models\ar\Page;
use prime\models\ar\Project;
use yii\helpers\Html;

/**
 * Class Menu
 * Implements a side menu for project pages
 * @package prime\widgets\menu
 */
class ProjectPageMenu extends SideMenu
{
    public Project $project;

    public PageInterface $currentPage;

    public $params = [];

    public function init()
    {
        $this->title = $this->project->getDisplayField();
        parent::init();
    }

    protected function renderMenu()
    {
        foreach ($this->project->mainPages as $page) {
            $this->renderPageMenu($page);
        }
    }

    /**
     * @return bool whether the link is active
     */
    protected function renderPageLink(PageInterface $page): bool
    {
        $options = [];
        if (
            $page->getId() === $this->currentPage->getId()
            && $page->getParentId() === $this->currentPage->getParentId()
        ) {
            Html::addCssClass($options, 'active');
            $result = true;
        } else {
            $result = false;
        }
        $route = empty($page->children) ? array_merge($this->params, [
            'project/view',
            'id' => $this->project->id,
            'parent_id' => $page->getParentId(),
            'page_id' => $page->getId(),
        ]) : null;
        echo Html::a(\Yii::t('app.pagetitle', $page->getTitle()), $route, $options);

        return $result;
    }

    /**
     * @param Page $page
     * @return bool whether this menu contains an active child.
     */
    protected function renderPageMenu(PageInterface $page): bool
    {
        $headerOptions = [];
        ob_start();
        $result = false;
        foreach ($page->getChildPages() as $child) {
            if ($this->renderPageMenu($child) && ! $result) {
                $result = true;
                Html::addCssClass($headerOptions, 'expanded');
            }
        }

        $sub = ob_get_clean();

        if (empty($sub)) {
            return $this->renderPageLink($page);
        }
        echo Html::beginTag('section');
        echo Html::tag('header', Html::a(\Yii::t('app.pagetitle', $page->title)) . Icon::chevronRight([
            'class' => 'collapsed-only',
        ]) . Icon::chevronDown([
            'class' => 'expanded-only',
        ]), $headerOptions);
        echo $sub;
        echo Html::endTag('section');
        return $result;
    }
}
