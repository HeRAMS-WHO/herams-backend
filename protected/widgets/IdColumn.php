<?php

declare(strict_types=1);

namespace prime\widgets;

use prime\traits\FunctionGetterColumn;
use yii\grid\DataColumn;

class IdColumn extends DataColumn
{
    use FunctionGetterColumn;

    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->filterAttribute = $this->attribute = 'id';
        $this->options['style']['width'] = '100px';
    }
}
