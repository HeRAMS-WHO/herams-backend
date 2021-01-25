<?php
declare(strict_types=1);

namespace prime\widgets;

use prime\helpers\Icon;
use prime\models\ActiveRecord;
use yii\base\NotSupportedException;
use yii\grid\DataColumn;
use yii\helpers\Html;

class IdColumn extends DataColumn
{
    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->attribute = 'id';
        $this->options['style']['width'] = '100px';
    }
}
