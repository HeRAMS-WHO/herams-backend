<?php


namespace prime\widgets\element;


use prime\helpers\Icon;
use prime\models\permissions\Permission;
use yii\base\Widget;
use yii\helpers\Html;

/**
 * Class Element
 * @package prime\widgets\element
 */
class Element extends Widget
{
    public $options = [];
    /** @var \prime\models\ar\Element  */
    private $element;

    public function __construct(\prime\models\ar\Element $element, $config = [])
    {
        parent::__construct($config);
        $this->element = $element;
    }

    public function init()
    {
        parent::init();
        $options = $this->options;
        Html::addCssClass($options, strtr(get_class($this), ['\\' => '_']));
        Html::addCssClass($options, 'element');
        $options['id'] = $this->getId();
        echo Html::beginTag('div', $options);
    }

    public function run()
    {
        parent::run();

        if (isset($this->element->id, $this->element->page->project) && \Yii::$app->user->can(Permission::PERMISSION_ADMIN, $this->element->page->project)) {
            echo Html::a(Icon::edit(), ['/element/update', 'id' => $this->element->id], [
                'style' => [
                    'position' => 'absolute',
                    'right' => '5px',
                    'padding' => '10px',
                    'top' => '5px',
                    'border-radius' => '50%',
                    'overflow' => 'hidden',
                    'z-index' => 10000,
                    'background-color' => '#eeeeee'
                ]
            ]);
        }
        echo Html::endTag('div');
    }


}