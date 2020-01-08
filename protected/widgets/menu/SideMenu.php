<?php


namespace prime\widgets\menu;


use prime\interfaces\PageInterface;
use prime\models\ar\Page;
use prime\helpers\Icon;
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
    public $title;
    public $footer;
    public $foldable = false;

    protected function registerClientScript()
    {
        $id = Json::encode($this->getId());
        if($this->foldable == true) {
        $js = <<<JS
        
            document.getElementById($id).addEventListener('click', e =>  {
                if (e.target.matches('.toggleMenu')) {
                    e.target.closest('.menu').classList.toggle('expanded');
                }
            }, {
                passive: true
            });

JS;

        $this->view->registerJs($js);
        }

        $js = <<<JS
            document.getElementById($id).addEventListener('click', e =>  {
                if (e.target.matches('header *')) {
                    e.target.closest('header').classList.toggle('expanded');
                }
            }, {
                passive: true
            });

        JS;
        $this->view->registerJs($js);
        $this->view->registerAssetBundle(MenuBundle::class);
    }

    protected function renderMenu()
    {
        return 'ok';
    }

    public function init()
    {
        parent::init();
        $options = [
            'id' => $this->getId()
        ];

        Html::addCssClass($options, 'menu');
        if($this->foldable) Html::addCssClass($options, 'foldable');
        $this->registerClientScript();
        echo Html::beginTag('div', $options);
        echo Html::img("/img/HeRAMS.png");
        if($this->foldable == true) {
            echo Icon::chevronRight(['class'=>'toggleMenu']);
            echo Icon::chevronDown(['class'=>'toggleMenu']);
        }
        echo Html::tag('h1', $this->title);
        echo Html::tag('hr');
        echo Html::beginTag('nav');
    }


    public function run()
    {
        $this->renderMenu();
        echo Html::endTag('nav');
        echo $this->footer;
        echo Html::endTag('div');
    }




}