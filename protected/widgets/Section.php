<?php

declare(strict_types=1);

namespace prime\widgets;

use herams\common\models\PermissionOld;
use prime\interfaces\CanCurrentUser;
use yii\base\Widget;
use yii\helpers\Html;
use yii\web\User;

class Section extends Widget
{
    public iterable $actions = [];

    public string $header;

    public array $headerOptions = [];

    public array $options = [];

    private int $outputBuffer;

    public string $permission;

    public object $subject;

    private function filterButtons(): iterable
    {
        foreach ($this->actions as $action) {
            if (isset($action['visible']) && $action['visible'] === false) {
                continue;
            }

            if (isset($action['permission'])) {
                if ($action['permission'] instanceof \Closure) {
                    if (! $action['permission']($this->subject, $this->getUserComponent())) {
                        continue;
                    }
                } elseif (isset($this->subject) && $this->subject instanceof CanCurrentUser) {
                    if ($this->subject->canCurrentUser($action['permission'])) {
                        yield $action;
                    }
                    continue;
                } elseif (! $this->getUserComponent()->can($action['permission'], $this->subject ?? [])) {
                    continue;
                }
            }

            yield $action;
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
        return $this->withPermission(PermissionOld::PERMISSION_ADMIN);
    }

    public function withActions(array $actions): self
    {
        $this->actions = $actions;
        return $this;
    }

    public function withHeader(string $header, array $options = []): self
    {
        $this->header = $header;
        $this->headerOptions = $options;
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
            .Section > h1 {
                margin: 0;
            }
            .Section:only-of-type > header > h2 {
                display: none;
                margin-bottom: 0;
            }
            
            .Section > header > *:last-child {
                margin-left: auto;
            }
            
            .Section header {
                display: flex;
                flex-wrap: wrap;
                flex-grow: 0;
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
                display: flex;
                flex-direction: column;
                flex-grow: 1;
            }
            .Section > header ~ * {
                flex-grow: 1;
            } 
            .Section:only-child {
                height: 100%;
                margin-bottom: 0px;
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
        if (isset($this->permission) && ! $this->getUserComponent()->can($this->permission, $this->subject)) {
            ob_get_clean();
            return '';
        }

        $options = $this->options;
        Html::addCssClass($options, 'Section');
        $result = Html::beginTag('section', $options);
        $result .= Html::beginTag('header');
        $result .= Html::tag('h2', $this->header ?? '', $this->headerOptions);

        $result .= NavigationButtonGroup::widget([
            'buttons' => $this->filterButtons(),
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
