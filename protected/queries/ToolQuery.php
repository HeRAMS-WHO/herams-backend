<?php

namespace app\queries;

use prime\components\ActiveQuery;

/**
 * Class ToolQuery
 * @package app\queries
 * @method \prime\models\ar\Project one();
 */
class ToolQuery extends ActiveQuery
{
    /**
     * @return self
     */
    public function notHidden()
    {
        return $this->andWhere(['hidden' => 0]);
    }

}