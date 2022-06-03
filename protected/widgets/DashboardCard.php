<?php

declare(strict_types=1);

namespace prime\widgets;

use prime\assets\DashboardCardsBundle;
use prime\components\View;
use prime\helpers\Icon;
use prime\objects\enums\ChartType;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;
use function iter\chain;
use function iter\toArray;

class DashboardCard extends Widget
{
    private ChartType $type;

    private string $title;

    private array $points;

    private bool $shadowRoot = false;

    private DashboardCardsBundle $bundle;

    private int $n;

    private string|array $updateRoute;

    public function withShadowRoot(): static
    {
        $this->shadowRoot = true;
        return $this;
    }

    public function withUpdateRoute(string|array $route): static
    {
        $this->updateRoute = $route;
        return $this;
    }

    public function withoutShadowRoot(): static
    {
        $this->shadowRoot = false;
        return $this;
    }

    public function withType(ChartType $type): static
    {
        $this->type = $type;
        return $this;
    }

    public function withData(array $points): static
    {
        $this->points = $points;
        return $this;
    }

    public function withN(int $number): static
    {
        $this->n = $number;
        return $this;
    }

    public function withTitle(string $title): static
    {
        $this->title = $title;
        return $this;
    }

    public function finish(): void
    {
        self::end();
    }

    public function init(): void
    {
        parent::init();
        $this->bundle = DashboardCardsBundle::register($this->view);
    }

    public function run(): string
    {
        $config = [
            'type' => $this->type->value,
            'n' => $this->n ?? null,
            'data' => $this->points,
            'title' => $this->title,
            'typeSelector' => false,
        ];
        $jsonId = json_encode($this->getId());
        $jsonConfig = json_encode($config);

        $js = <<<JS
          {$this->bundle->getImport("DashboardCard")}
            const target = document.getElementById($jsonId);
            const root = target.hasAttribute('data-use-shadow-root') ? target.attachShadow({mode: 'open'}) : target;

          const app = new DashboardCard({
            target: root,
            props: $jsonConfig,
          });
        
        JS;

        $css = <<<CSS
        .card-widget {
            background-color: white;
            --padding: 10px;
            padding: var(--padding);
            overflow: hidden;
            min-height: 250px;
            position: relative;
        }
        
        .card-widget > .card {
            height: calc(100% - var(--padding));
        }
        
        .card-widget select {
            position: absolute;
            right: 10px;
            top: 10px;
            z-index: 10;
        }
        
        .card-widget .count:before {
            content: var(--count-prefix, "n = ");
        }
        .card-widget header {
            position: absolute;
            left: 10px;
            top: 10px;
        }
        
        .card-widget footer {
            display: block;
            text-align: center;
        }
        .card-widget .chart-content {
            margin-top: 24px;
            height: calc(100% - 24px);
        }
        
        [data-update-uri] {
            perspective: 1px;
        }
        .card-widget .update, .card-widget .update:visited {
            display: block;
            color: white;
            background-color: rgba(0, 0, 0, 0.3);
            border: none;
            font-weight: bold;
            width: 50px;
            height: 50px;
            position: absolute;
            padding-left: 25px;
            padding-top: 25px;
            right: 0;
            bottom: 0;
            clip-path: polygon(0% 100%, 100% 0%, 100% 100%);
        }
        
        CSS;
        $this->view->registerJs($js, View::POS_MODULE);
        $this->view->registerCss($css);

        $updateButton = false && isset($this->updateRoute) ? Html::a(Icon::edit(), Url::to($this->updateRoute), [
            'class' => 'update',
        ]) : '';
        return Html::tag('div', $updateButton, [
            'id' => $this->getId(),
            'class' => ['card-widget'],
            'data' => [
                'update-uri' => isset($this->updateRoute) ? Url::to($this->updateRoute) : null,
                'use-shadow-root' => $this->shadowRoot,
                '',
            ],
        ]); //.  "<!-- " . json_encode($config['data'], JSON_PRETTY_PRINT)."-->";
    }
}
