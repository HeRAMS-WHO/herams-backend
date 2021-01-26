<?php
declare(strict_types=1);

namespace prime\widgets;

use yii\base\Widget;
use yii\helpers\Html;

class Tabs extends Widget
{
    public array $items = [];
    public array $options = [];
    public function init()
    {
        parent::init();
        $this->options['id'] = $this->getId();
        ob_start();
        echo Html::beginTag('tabbed-content', $this->options);
    }

    public function run(): string
    {
        $css = <<<CSS
            tabbed-content {
                transition: opacity 2s; 
            }
            
            tabbed-content:not(:defined) { 
                opacity: 0; 
            }

            /* CSS For the content wrapper */
            tabbed-content::part(content) {
            }
            
            /* CSS For the header wrapper */
            tabbed-content::part(header) {
                grid-gap: 1px;
            }
            
            tabbed-content [slot=header].active {
                background-color: white;
            }

            tabbed-content span {
                background-color: var(--tab-button-background-color);
                color: var(--tab-button-text-color);
                font-size: 13px;
                border-radius: 6px 6px 0 0;               
                padding: 15px;
                transition: color 0.2s, background 0.2s, border 0.2s;
            }
CSS;

        $this->view->registerCss($css);
        $this->view->registerJsFile('/js/components/tabbed-content.js', ['type' => 'module']);
        foreach ($this->items as $item) {
            // Render label then content.
            echo Html::tag('span', $item['label'], ['slot' => 'header']);
            echo Html::tag('div', $item['content'], ['slot' => 'content']);
        }
        echo Html::endTag('tabbed-content');
        return ob_get_clean();
    }
}
