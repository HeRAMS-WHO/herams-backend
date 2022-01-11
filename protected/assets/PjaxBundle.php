<?php
declare(strict_types=1);

namespace prime\assets;

use yii\widgets\PjaxAsset;

class PjaxBundle extends PjaxAsset
{
    public $baseUrl = '@npm/yii2-pjax';
    public $sourcePath = null;
}
