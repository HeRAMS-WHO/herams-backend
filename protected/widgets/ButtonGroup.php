<?php

declare(strict_types=1);

namespace prime\widgets;

use yii\base\Widget;
use yii\helpers\Html;

use yii\helpers\Url;
use function iter\mapWithKeys;
use function iter\toArrayWithKeys;

class ButtonGroup extends Widget
{
    public const STYLE_DELETE = 'delete';

    public const TYPE_LINK = 'link';

    public const TYPE_RAW = 'raw';

    public const TYPE_DELETE_BUTTON = 'delete_button';

    public const TYPE_SUBMIT = 'submit';

    public string $defaultButtonType = 'link';

    public string $tagName = 'div';

    public array $options = [];

    private int $outputBuffer;

    public iterable $buttons = [];

    public function init()
    {
        parent::init();
        $this->initCss();
        ob_start();
        $this->outputBuffer = ob_get_level();
        ob_implicit_flush(false);

        $options = $this->options;
        Html::addCssClass($options, 'ButtonGroup');

        echo Html::beginTag($this->tagName, $options);
    }

    private function initCss(): void
    {
        $css = <<<CSS
            .ButtonGroup {
                white-space: nowrap;
            }
            .ButtonGroup:empty {
                display: none;
            }
            .ButtonGroup > *:not(:first-child) {
                border-top-left-radius: 0;
                border-bottom-left-radius: 0;
            }
            .ButtonGroup > *:not(:last-child) {
                border-top-right-radius: 0;
                border-bottom-right-radius: 0;
            }

        CSS;
        $this->view->registerCss($css, [], self::class);
    }

    private function renderButtons(): void
    {
        foreach ($this->buttons as $button) {
            if (isset($button['visible']) && $button['visible'] === false) {
                continue;
            }
            $type = is_string($button) ? 'raw' : $button['type'] ?? $this->defaultButtonType;
            switch ($type) {
                case self::TYPE_RAW:
                    echo $button;
                    break;
                case self::TYPE_LINK:
                    $this->renderLinkButton($button);
                    break;
                case self::TYPE_SUBMIT:
                    $this->renderSubmitButton($button);
                    break;
                case self::TYPE_DELETE_BUTTON:
                    $this->renderDeleteButton($button);
                    break;
                default:
                    throw new \InvalidArgumentException("Unknown button type: $type");
            }
        }
    }

    private function renderSubmitButton(array $button): void
    {
        $options = $button['buttonOptions'] ?? [];
        Html::addCssClass($options, ['btn', 'btn-' . ($button['style'] ?? 'default')]);
        echo Html::submitButton(($button['icon'] ?? '') . $button['label'], $options);
    }

    private function renderLinkButton(array $button): void
    {
        $options = $button['linkOptions'] ?? [];
        Html::addCssClass($options, ['btn', 'btn-' . ($button['style'] ?? 'default')]);
        $link = is_array($button['link']) ? toArrayWithKeys(mapWithKeys(static fn ($queryParam) => (
            $queryParam instanceof \Stringable
            ? (string) $queryParam
            : $queryParam
        ), $button['link'])) : $button['link'];
        echo Html::a(($button['icon'] ?? '') . $button['label'], $link, $options);
    }

    private function renderDeleteButton(array $button): void
    {
        $options = [
            'class' => ['btn', 'btn-delete'],
            'data-herams-action' => 'delete',
            'data-herams-confirm' => $button['confirm'] ?? \Yii::t('app', 'Are you sure you want to delete this item?'),
            'data-herams-endpoint' => Url::to($button['endpoint'], true),
            'data-herams-redirect' => Url::to($button['redirect'], true),
        ];
        echo Html::button(($button['icon'] ?? '') . $button['label'], $options);
    }

    public function run()
    {
        $this->renderButtons();
        echo Html::endTag($this->tagName);
        if (ob_get_level() !== $this->outputBuffer) {
            throw new \RuntimeException('Output buffers not properly handled');
        }
        return ob_get_clean();
    }
}
