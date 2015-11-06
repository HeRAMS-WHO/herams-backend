<?php

namespace app\queries;

use prime\components\ActiveQuery;

class ProjectQuery extends ActiveQuery
{
    /**
     * @return self
     */
    public function closed()
    {
        return $this->andWhere(['not', ['closed' => null]]);
    }

    /**
     * @return self
     */
    public function notClosed()
    {
        return $this->andWhere(['closed' => null]);
    }

}