<?php


namespace prime\components;


use yii\base\NotSupportedException;

class Finder extends \dektrium\user\Finder
{
    public function findUserByUsername($username)
    {
        throw new NotSupportedException();
    }

    public function findUserByUsernameOrEmail($usernameOrEmail)
    {
        return parent::findUserByEmail($usernameOrEmail);
    }


}