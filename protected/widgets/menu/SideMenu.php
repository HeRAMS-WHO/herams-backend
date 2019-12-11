<?php


namespace prime\widgets\menu;


use prime\interfaces\PageInterface;
use prime\models\ar\Page;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Json;

/**
 * Class Menu
 * Implements a side menu for project pages
 * @package prime\widgets\menu
 */
class SideMenu extends Widget
{
    public $params = [];

    protected function registerClientScript()
    {
        $id = Json::encode("#{$this->getId()}");

        $js = <<<JS
            $($id).on('click', 'header', function() {
                $(this).toggleClass('expanded');
            })


JS;

        $this->view->registerJs($js);
    }
    public function run()
    {
        $options = [
            'id' => $this->getId()
        ];

        Html::addCssClass($options, 'menu');
        $this->registerClientScript();
        echo Html::beginTag('div', $options);
        echo Html::img("/img/HeRAMS.png");
        echo Html::tag('h1', $this->project->getDisplayField());
        echo Html::beginTag('div',['class'=>'line']);
        echo Html::endTag('div');
        echo Html::beginTag('nav');
        foreach($this->project->pages as $page) {
            $this->renderPageMenu($page);
        }
        echo Html::endTag('nav');
        echo Html::endTag('div');
    }

    /**
     * @return bool whether the link is active
     */
    protected function renderPageLink(PageInterface $page): bool
    {
        $options = [];
        if ($page->getId() === $this->currentPage->getId()
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
            'page_id' => $page->getId()
        ]) : null;
        echo Html::a($page->getTitle(), $route, $options);

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
        foreach($page->getChildPages($this->survey) as $child) {
            if ($this->renderPageMenu($child) && !$result) {
                $result = true;
                Html::addCssClass($headerOptions, 'expanded');
            }
        }

        $sub = ob_get_clean();

        if (empty($sub)) {
            return $this->renderPageLink($page);
        }
        echo Html::beginTag('section');
        echo Html::tag('header', Html::a($page->title), $headerOptions);
        echo $sub;
        echo Html::endTag('section');
        return $result;
    }


}
