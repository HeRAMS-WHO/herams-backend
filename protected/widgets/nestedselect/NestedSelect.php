<?php


namespace prime\widgets\nestedselect;


use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\InputWidget;

class NestedSelect extends InputWidget
{
    public $placeholder;
    public $multiple = '(multiple)';
    public $allowMultiple = true;

    public $expanded = false;
    public $header = true;

    public $items;

    public $groupLabelOptions = [];

    private function getValue()
    {
        return $this->value ?? $this->model->{$this->attribute} ?? [];
    }

    private function getName(): string
    {
        return $this->name ?? Html::getInputName($this->model, $this->attribute);
    }

    public function init()
    {
        parent::init();
        $options = $this->options;
        Html::addCssClass($options, 'NestedSelect');
        $options['data']['multiple'] = $this->multiple;
        $options['data']['placeholder'] = $this->placeholder;
        $this->view->registerAssetBundle(AssetBundle::class);
        echo Html::beginTag('div', $options);
    }

    private function indent(string $out, int $level = 0)
    {
        echo str_repeat(' ', $level * 4 + 4) . $out . "\n";
    }

    private function renderScalar(string $value, string $label, array $stack)
    {

        array_push($stack, $label);
        $this->indent(Html::checkbox($this->getName() . '[]', in_array($value, $this->getValue()), [
            'value' => $value,
            'labelOptions' => [
                'class' => 'option',
            ],
            'label' => $label //implode(' / ', $stack)
        ]), count($stack));
    }

    private function renderGroup(string $label, array $items, array $stack)
    {
        array_push($stack, $label);
        $level = count($stack);
        $this->indent(Html::beginTag('div'), $level);
        $groupLabelOptions = $this->groupLabelOptions;
        Html::addCssClass($groupLabelOptions, 'group');
        $this->indent(Html::checkbox('', false, [
            'label' => $label,
            'labelOptions' => $groupLabelOptions

        ]));
        $this->renderOptions($items, $stack);
        $this->indent('</div>', $level);
    }

    private function renderOptions(array $items, array $stack = []): void
    {
        foreach($items as $value => $label) {
            if (is_scalar($label)) {
                $this->renderScalar($value, $label, $stack);
            } elseif (is_array($label)) {
                $this->renderGroup($value, $label, $stack);
            }
        }
    }

    public function run()
    {
        parent::run();
        $currentOptions = [
            'class' => ['current']
        ];
        if ($this->expanded) {
            Html::addCssClass($currentOptions, 'expanded');
        }
        echo Html::tag('span', null, $currentOptions);
        echo Html::beginTag('div', ['class' => 'options']);
        $this->renderOptions($this->items);
        $id = Json::encode($this->options['id']);
        $this->view->registerJs(<<<JS
    NestedSelect.updateTitle(document.getElementById($id));
    NestedSelect.updateGroupBoxes(document.getElementById($id));
JS
        );


        echo Html::endTag('div');
        echo Html::endTag('div');
    }


}