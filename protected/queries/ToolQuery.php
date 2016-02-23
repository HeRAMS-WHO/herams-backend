<?php

namespace app\queries;

use prime\components\ActiveQuery;

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