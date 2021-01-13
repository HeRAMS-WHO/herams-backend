<?php
declare(strict_types=1);

namespace prime\widgets;

use yii\base\Widget;
use yii\helpers\Html;

class Section extends Widget
{
    private int $outputBuffer;
    public object $subject;
    public iterable $actions = [];
    public string $header;

    private function filterButtons(): iterable
    {
        foreach ($this->actions as $action) {
            if (isset($action['visible']) && $action['visible'] === false) {
                continue;
            }

            if (!isset($action['permission']) || \Yii::$app->user->can($action['permission'], $this->subject ?? [])) {
                yield $action;
            }
        }
    }

    public function init()
    {
        parent::init();
        $this->initCss();

        ob_start();
        $this->outputBuffer = ob_get_level();
        ob_implicit_flush(0);
        echo Html::beginTag('section', ['class' => 'Section']);
        echo Html::beginTag('header');
        echo Html::tag('h1', $this->header ?? '');

        echo NavigationButtonGroup::widget([
            'buttons' => $this->filterButtons()
        ]);
        echo Html::endTag('header');
    }



    private function initCss(): void
    {
        $css = <<<CSS
        
            .Section:only-child > header > h1 {
                display: none;
                margin-bottom: 0;
            }
            
            
            
            .Section > header > *:last-child {
                margin-left: auto;
            }
            
            .Section header {
                display: flex;
                flex-wrap: wrap;
                justify-content: space-between;
            }
            .Section header > h1 {
                margin: 0;
                white-space: nowrap;
                flex-grow: 1;
                font-size: 1rem;
                text-align: left;
            }

            .Section header > h1, .Section header > .NavigationButtonGroup {
                margin-bottom: 10px;
            }

        CSS;
        $this->view->registerCss($css, [], self::class);
    }

    public function run(): string
    {
        echo Html::endTag('section');
        if (ob_get_level() !== $this->outputBuffer) {
            throw new \RuntimeException('Output buffers not properly handled');
        }
        return ob_get_clean();
    }
}
