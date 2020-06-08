<?php

namespace prime\components;

use SamIT\Yii2\VirtualFields\VirtualFieldQueryTrait;

class ActiveQuery extends \yii\db\ActiveQuery
{
    use VirtualFieldQueryTrait;
}
