<?php 

namespace prime\models;

class User extends \dektrium\user\models\User {

    public function getUsername()
    {
        return $this->email;
    }

    /**
     * Dummy function because Dektrium user module uses username
     * @param $value
     */
    public function setUsername($value)
    {

    }

    public function getGravatarUrl ($size = 24) {
        return "http://gravatar.com/avatar/" . md5(strtolower($this->email)) . "?s=" . $size;
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['create'] = ['email', 'password'];
        $scenarios['register'] = ['email', 'password'];
        $scenarios['settings'] = ['email', 'password'];
        return $scenarios;
    }
}