<?php 

namespace prime\models;

/**
 * Class User
 * @package prime\models
 * @property Profile $profile
 */
class User extends \dektrium\user\models\User {

    public function getGravatarUrl ($size = 24)
    {
        return "http://gravatar.com/avatar/" . md5(strtolower($this->email)) . "?s=" . $size;
    }

    public function getName()
    {
        if(!isset($this->profile)) {
            return $this->email;
        } else {
            return implode(
                ' ',
                [
                    $this->profile->first_name,
                    $this->profile->last_name
                ]
            );
        }
    }

    /**
     * The project find function only returns projects a user has at least read access to
     * @return $this
     */
    public function getProjects()
    {
        return Project::find();
    }

    public function getUsername()
    {
        return $this->email;
    }

    public function rules()
    {
        $rules = parent::rules();
        unset($rules['usernameRequired']);
        unset($rules['usernameMatch']);
        unset($rules['usernameLength']);
        unset($rules['usernameUnique']);
        unset($rules['usernameTrim']);
        return $rules;
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['create'] = ['email', 'password'];
        $scenarios['register'] = ['email', 'password'];
        $scenarios['settings'] = ['email', 'password'];
        return $scenarios;
    }

    /**
     * Dummy function because Dektrium user module uses username
     * @param $values
     */
    public function setUsername($value)
    {

    }
}