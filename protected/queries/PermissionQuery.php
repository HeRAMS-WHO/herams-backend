<?php


namespace app\queries;


use prime\components\ActiveQuery;
use yii\db\ActiveRecord;

class PermissionQuery extends ActiveQuery
{

    public function andWithSource(ActiveRecord $authorizable)
    {
        return $this->andWhere([
            'source_id' => $authorizable->id,
            'source' => get_class($authorizable)
        ]);
    }

    public function andWithTarget(ActiveRecord $authorizable)
    {
        return $this->andWhere([
            'target_id' => $authorizable->id,
            'target' => get_class($authorizable)
        ]);
    }

}