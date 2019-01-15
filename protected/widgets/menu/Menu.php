<?php


namespace prime\widgets\menu;


use prime\models\ar\Page;
use prime\models\ar\Tool;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Json;

class Menu extends Widget
{
    /** @var Tool */
    public $project;

    /** @var Page */
    public $currentPage;


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
        echo Html::img("https://herams.org/img/HeRAMS.png");
        echo Html::tag('h1', $this->project->getDisplayField());
        echo Html::beginTag('nav');
        foreach($this->project->pages as $page) {
            $this->renderPageMenu($page);
        }
        echo Html::endTag('nav');
        echo Html::endTag('div');
    }

    /**
     * @param Page $page
     * @return bool whether the link is active
     */
    protected function renderPageLink(Page $page): bool
    {
        $options = [];
        if ($page->id === $this->currentPage->id) {
            Html::addCssClass($options, 'active');
        }
        $route = empty($page->children) ? ['projects/view', 'id' => $this->project->id, 'page_id' => $page->id] : null;
        echo Html::a($page->title, $route, $options);

        return $page->id === $this->currentPage->id;
    }

    /**
     * @param Page $page
     * @return bool whether this menu contains an active child.
     */
    protected function renderPageMenu(Page $page): bool
    {
        // Check if page has children.
        if (empty($page->children)) {
            return $this->renderPageLink($page);
        }


        $headerOptions = [];
        echo Html::beginTag('section');
        ob_start();
        $result = false;
        foreach($page->children as $child) {
            if ($this->renderPageMenu($child) && !$result) {
                $result = true;
                Html::addCssClass($headerOptions, 'expanded');
            }
        }

        $sub = ob_get_clean();
        echo Html::tag('header', Html::a($page->title), $headerOptions);
        echo $sub;
        echo Html::endTag('section');
        return $result;
    }


}