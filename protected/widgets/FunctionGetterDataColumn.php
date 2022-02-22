<?php

declare(strict_types=1);

namespace prime\widgets;

use prime\traits\FunctionGetterColumn;
use yii\grid\DataColumn;

class FunctionGetterDataColumn extends DataColumn
{
    use FunctionGetterColumn;
}
