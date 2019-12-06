<?php
declare(strict_types=1);

namespace prime\models\ar;


use prime\models\ActiveRecord;
use yii\base\NotSupportedException;
use yii\web\IdentityInterface;

class Key extends ActiveRecord implements IdentityInterface
{

    /**
     * @inheritDoc
     */
    public static function findIdentity($id)
    {
        return self::findOne(['id' => $id]);
    }

    /**
     * @inheritDoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // Split the token in to id and hash.
        if (preg_match('^(?P<id>\d+)\|(?P<secret>.*)$', $token, $matches)) {
            $token = self::findIdentity($matches['id']);
            if (password_verify($token['secret'], $token->hash)) {
                return $token;
            }
        }
        return self::findOne(['token' => $token]);
    }

    /**
     * @inheritDoc
     */
    public function getId()
    {
        return $this->getAttribute('id');
    }

    /**
     * @inheritDoc
     */
    public function getAuthKey()
    {
        throw new NotSupportedException();
    }

    /**
     * @inheritDoc
     */
    public function validateAuthKey($authKey)
    {
        return false;
    }
}