<?php


namespace prime\widgets\menu;

use yii\base\Widget;
use prime\interfaces\PageInterface;
use prime\models\ar\Permission;
use yii\helpers\Html;

/**
 * Class Menu
 * Implements a tab menu for admin pages
 * @package prime\widgets\menu
 */
class TabMenu extends Widget
{
    /** @var Array */
    public $tabs;

    /** @var PageInterface */
    public $currentPage;



    public function init()
    {
        parent::init();
    }


    protected function renderMenu()
    {

        echo Html::beginTag('div', ['class' => 'tabs']);
        foreach ($this->tabs as $tab) {
            $options = ['class' => 'btn btn-tab'];
            $options['class'] .= $this->currentPage === $tab['url'][0] ? ' active' : null;
            echo Html::a($tab['title'], $tab['url'], $options);
        }
        echo Html::endTag('div');
    }

    public function run()
    {
        $this->renderMenu();
    }
}
