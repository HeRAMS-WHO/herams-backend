<?php

namespace prime\widgets\element;

use herams\common\models\PermissionOld;
use prime\helpers\Icon;
use yii\base\Widget;
use yii\helpers\Html;

class Element extends Widget
{
    public $options = [];

    protected \herams\common\domain\element\Element $element;

    public int $width = 1;

    public int $height = 1;

    public function __construct(\herams\common\domain\element\Element $element, array $config = [])
    {
        $this->element = $element;
        foreach ($config as $key => $value) {
            if ($this->canSetProperty($key)) {
                $this->$key = $value;
            }
        }
        parent::__construct();
    }

    public function run(): string
    {
        $options = $this->options;
        Html::addCssClass($options, strtr(get_class($this), [
            '\\' => '_',
        ]));
        Html::addCssClass($options, 'element');
        $options['id'] = $this->getId();
        $options['style'] = array_merge($options['style'] ?? [], [
            'grid-row' => 'span ' . $this->height,
            'grid-column' => 'span ' . $this->width,
        ]);
        $result = Html::beginTag('div', $options);

        if (isset($this->element->id, $this->element->page->project) && \Yii::$app->user->can(PermissionOld::PERMISSION_WRITE, $this->element)) {
            $result .= Html::a(Icon::edit(), [
                '/element/update',
                'id' => $this->element->id,
            ], [
                'style' => [
                    'position' => 'absolute',
                    'right' => '5px',
                    'padding' => '10px',
                    'top' => '5px',
                    'border-radius' => '50%',
                    'overflow' => 'hidden',
                    'z-index' => 10000,
                    'background-color' => '#eeeeee',
                ],
            ]);
        }
        $result .= Html::endTag('div');

        return $result;
    }
}
