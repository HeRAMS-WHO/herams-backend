<?php


namespace app\components;


use dektrium\user\clients\ClientInterface;

class LinkedIn extends \yii\authclient\clients\LinkedIn implements ClientInterface
{

    /** @return string|null User's email */
    public function getEmail()
    {
        return '@todo';
        // TODO: Implement getEmail() method.
    }

    /** @return string|null User's username */
    public function getUsername()
    {
        return '@todo';
        // TODO: Implement getUsername() method.
    }
}