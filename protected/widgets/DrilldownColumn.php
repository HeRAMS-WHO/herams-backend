<?php

declare(strict_types=1);

namespace prime\widgets;

use prime\helpers\Icon;
use prime\interfaces\CanCurrentUser;
use prime\traits\FunctionGetterColumn;
use yii\grid\DataColumn;
use yii\helpers\Html;

class DrilldownColumn extends DataColumn
{
    use FunctionGetterColumn;

    public ?string $permission;
    public \Closure $link;
    public ?string $icon;

    public array $linkOptions = [];
    public function __construct($config = [])
    {
        $this->icon = Icon::eye();
        parent::__construct($config);
    }

    public function renderDataCellContent($model, $key, $index): string
    {
        $content = parent::renderDataCellContent($model, $key, $index);
        if (
            !isset($this->permission)
            || \Yii::$app->user->can($this->permission, $model)
            || ($model instanceof CanCurrentUser && $model->canCurrentUser($this->permission))
        ) {
            return Html::a("{$content} {$this->icon}", ($this->link)($model), $this->linkOptions);
        } else {
            return $content;
        }
    }
}
