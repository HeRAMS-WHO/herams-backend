<?php

declare(strict_types=1);

namespace prime\components;

use prime\objects\BreadcrumbCollection;
use yii\helpers\Html;

class View extends \yii\web\View
{
    public bool $autoAddTitleToBreadcrumbs = true;
    private BreadcrumbCollection $breadCrumbCollection;

    public function __construct($config = [])
    {
        $this->breadCrumbCollection = new BreadcrumbCollection();
        parent::__construct($config);
    }

    public function getBreadcrumbCollection(): BreadcrumbCollection
    {
        return $this->breadCrumbCollection;
    }

    public function registerJs($js, $position = \yii\web\View::POS_READY, $key = null)
    {
        // Not calling parent to prevent registration of JQueryAsset.
        $key = $key ?: md5($js);
        $this->js[$position][$key] = $js;
    }

    protected function renderBodyEndHtml($ajaxMode)
    {
        $lines = [];

        if (!empty($this->jsFiles[self::POS_END])) {
            $lines[] = implode("\n", $this->jsFiles[self::POS_END]);
        }

        if ($ajaxMode) {
            $scripts = [];
            if (!empty($this->js[self::POS_END])) {
                $scripts[] = implode("\n", $this->js[self::POS_END]);
            }
            if (!empty($this->js[self::POS_READY])) {
                $scripts[] = implode("\n", $this->js[self::POS_READY]);
            }
            if (!empty($this->js[self::POS_LOAD])) {
                $scripts[] = implode("\n", $this->js[self::POS_LOAD]);
            }
            if (!empty($scripts)) {
                $lines[] = Html::script(implode("\n", $scripts));
            }
        } else {
            if (!empty($this->js[self::POS_END])) {
                $lines[] = Html::script(implode("\n", $this->js[self::POS_END]));
            }
            if (!empty($this->js[self::POS_READY])) {
                $js = "document.addEventListener('DOMContentLoaded', function() {\n";
                foreach ($this->js[self::POS_READY] as $script) {
                    $js .= "$script\n";
                };
                $js .= "\n}, { passive: true });";
                $lines[] = Html::script($js);
            }
            if (!empty($this->js[self::POS_LOAD])) {
                $js = "document.addEventListener('load', function () {\n" . implode("\n", $this->js[self::POS_LOAD]) . "\n});";
                $lines[] = Html::script($js);
            }
        }

        return empty($lines) ? '' : implode("\n", $lines);
    }

    public function registerJsVar($name, $value, $position = self::POS_HEAD)
    {
        $js = sprintf('const %s = %s;', $name, \yii\helpers\Json::htmlEncode($value));
        $this->registerJs($js, $position, $name);
    }
}
