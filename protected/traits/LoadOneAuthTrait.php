<?php
namespace prime\traits;

use prime\models\permissions\Permission;

trait LoadOneAuthTrait{
    /**
     * Loads a model, throws an exception if user does not have permission.
     * @param int $id
     * @param string $priv
     * @param array $with
     * @return static ::class
     * @throws HttpException
     */
    public static function loadOne($id, array $with = [], $priv = Permission::PERMISSION_READ) {
        $result = static::find()->where(['id' => $id])->with($with)->userCan($priv)->one();
        if (!isset($result)) {
            throw new HttpException(404, \yii\helpers\StringHelper::basename(static::class) . " not found.");
        }
        return $result;
    }
}