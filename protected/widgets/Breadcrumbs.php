<?php

declare(strict_types=1);

namespace prime\widgets;

use prime\helpers\Icon;
use prime\interfaces\BreadcrumbInterface;
use prime\objects\BreadcrumbCollection;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\helpers\Html;
use function iter\map;

class Breadcrumbs extends Widget
{
    private BreadcrumbCollection $breadcrumbCollection;

    public function forCollection(BreadcrumbCollection $breadcrumbCollection): self
    {
        $this->breadcrumbCollection = $breadcrumbCollection;
        return $this;
    }

    public function run(): string
    {
        if (! isset($this->breadcrumbCollection)) {
            throw new InvalidConfigException("Breadcrumb collection was not configured");
        }
        // Render the breadcrumb widget.
        return \yii\widgets\Breadcrumbs::widget([
            'itemTemplate' => Html::tag('li', "{link}" . Icon::chevronRight([
                'class' => 'separator',
            ])),
            'homeLink' => [
                'label' => \Yii::t('app', 'Administration'),
                'url' => ['/admin'],
            ],
            'links' => map(fn (BreadcrumbInterface $breadcrumb) => [
                'label' => $breadcrumb->getLabel(),
                'encode' => false,
                'url' => $breadcrumb->getUrl(),
            ], $this->breadcrumbCollection),
        ]);
    }
}
