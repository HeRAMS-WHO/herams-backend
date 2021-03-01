<?php
declare(strict_types=1);

namespace prime\widgets;

use prime\models\ar\Permission;
use yii\base\Widget;
use yii\helpers\Html;
use yii\web\User;

class Section extends Widget
{

    private int $outputBuffer;
    public object $subject;
    public iterable $actions = [];
    public $options = [];
    public $permission;
    public string $header;

    private function filterButtons(): iterable
    {
        foreach ($this->actions as $action) {
            if (isset($action['visible']) && $action['visible'] === false) {
                continue;
            }

            if (!isset($action['permission']) || $this->getUserComponent()->can($action['permission'], $this->subject ?? [])) {
                yield $action;
            }
        }
    }

    public function forDangerousAction(): self
    {
        Html::addCssClass($this->options, ['dangerous']);
        return $this;
    }

    public function forAdministrativeAction(): self
    {
        Html::addCssClass($this->options, ['administrative']);
        return $this->withPermission(Permission::PERMISSION_ADMIN);
    }


    public function withHeader(string $header): self
    {
        $this->header = $header;
        return $this;
    }

    public function withPermission(string $permission): self
    {
        $this->permission = $permission;
        return $this;
    }

    public function withSubject($subject): self
    {
        $this->subject = $subject;
        return $this;
    }

    private function getUserComponent(): User
    {
        return \Yii::$app->user;
    }

    public function init()
    {
        parent::init();
        $this->initCss();
        $this->outputBuffer = ob_get_level();
        ob_start();
        ob_implicit_flush(false);
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
                font-size: 1.3rem;
                text-align: left;
            }

            .Section header > h1, .Section header > .NavigationButtonGroup {
                margin-bottom: 10px;
            }
            
            .Section {
                margin-bottom: 10px;
                padding: 10px;
            }
            
                        
            .Section.dangerous header {
                color: rgb(var(--danger));
            }
            
            .Section.administrative header {
                color: rgb(var(--info));
            }
            
            .Section.dangerous {
                border: 1px solid rgb(var(--danger));
                padding: 10px;
                background-color: rgba(var(--danger), 0.2);
            
            }
            
            .Section.administrative {
                border: 1px solid rgb(var(--info));
                padding: 10px;
                background-color: rgba(var(--info), 0.2);
            
            }
            

        CSS;
        $this->view->registerCss($css, [], self::class);
    }

    public function run(): string
    {
        if (isset($this->permission) && !$this->getUserComponent()->can($this->permission, $this->subject)) {
            ob_get_clean();
            return '';
        }

        $options = $this->options;
        Html::addCssClass($options, 'Section');
        $result = Html::beginTag('section', $options);
        $result .= Html::beginTag('header');
        $result .= Html::tag('h1', $this->header ?? '');

        $result .= NavigationButtonGroup::widget([
            'buttons' => $this->filterButtons()
        ]);

        $result .= Html::endTag('header');
        $result .= ob_get_clean();
        $result .= Html::endTag('section');
        if (ob_get_level() !== $this->outputBuffer) {
            throw new \RuntimeException('Output buffers not properly handled');
        }
        return $result;
    }
}
