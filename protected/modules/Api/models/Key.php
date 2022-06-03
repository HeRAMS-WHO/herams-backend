<?php

declare(strict_types=1);

namespace prime\modules\Api\models;

use prime\models\ActiveRecord;
use yii\base\NotSupportedException;
use yii\web\IdentityInterface;

class Key extends ActiveRecord implements IdentityInterface
{
    public static function findIdentity($id)
    {
        return self::findOne([
            'id' => $id,
        ]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        // Split the token in to id and hash.
        if (preg_match('/^(?P<id>\d+)\|(?P<secret>.*)$/', $token, $matches)) {
            $model = self::findIdentity($matches['id']);

            if (isset($model) && password_verify($matches['secret'], $model->hash)) {
                return $model;
            }
        }
    }

    public function getId()
    {
        return $this->getAttribute('id');
    }

    public function getAuthKey()
    {
        throw new NotSupportedException();
    }

    public function validateAuthKey($authKey)
    {
        return false;
    }
}
