<?php

namespace prime\components;

class ActiveQuery extends \yii\db\ActiveQuery
{
    use AuthorizationScopes;

    /**
     * Allows for inline cloning.
     * @return ActiveQuery
     */
    public function copy() {
        return clone $this;
    }

    public static function userCanScope(ActiveQuery $query, $operation, $userId = null)
    {
        return false;
    }




}