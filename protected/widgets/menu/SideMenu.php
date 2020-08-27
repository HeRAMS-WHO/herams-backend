<?php


namespace prime\widgets\menu;

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
    public $collapsible = false;

    protected function registerClientScript()
    {
        $id = Json::encode($this->getId());
        $js = <<<JS
            let elem = document.getElementById($id);
            elem.addEventListener('click', e =>  {
                // Handle header collapse
                if (e.target.matches('header *')) {
                    e.target.closest('header').classList.toggle('expanded');
                } else if (e.target.matches('.toggle')) {
                    elem.classList.toggle('expanded');
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

        Html::addCssClass($options, ['expanded', 'menu']);
        if ($this->collapsible) {
            Html::addCssClass($options, ['collapsible']);
        }

        $this->registerClientScript();
        echo Html::beginTag('div', $options);
        echo Html::a(Html::img("/img/HeRAMS.svg"), '/');
        // We always render the toggle so we can later enable / disable menu collapsing.
        echo Html::a(Icon::chevronRight(), '#', ['class' => ['toggle', 'collapsed-only']]);
        echo Html::a(Icon::chevronDown(), '#', ['class' => ['toggle', 'expanded-only']]);
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
