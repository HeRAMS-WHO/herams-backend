<?php 

namespace prime\models\ar;

use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Rsa\Sha512;
use Lcobucci\JWT\ValidationData;
use prime\models\permissions\Permission;
use prime\objects\Signature;
use yii\helpers\Url;
use yii\web\UrlManager;

/**
 * Class User
 * @package prime\models
 * @property Profile $profile
 * @property string $firstName
 * @property string $lastName
 * @property string $organization
 * @property string $office
 * @property string $country
 * @property string $gravatarUrl
 * @property string $name
 */
class User extends \dektrium\user\models\User {

    public $last_login_at;
    const NON_ADMIN_KEY = 'safe';

    public function createSignature(\DateTimeImmutable $time = null)
    {
        return new Signature(
            $this->email,
            $this->id,
            $this->name
        );
    }

    public function getGravatarUrl ($size = 256)
    {
        return "//gravatar.com/avatar/" . md5(strtolower($this->email)) . "?s=" . $size . "&d=blank";
    }

    public function getFirstName()
    {
        return isset($this->profile) ? $this->profile->first_name : null;
    }

    public function getLastName() {
        return isset($this->profile) ? $this->profile->last_name : null;
    }
    public function getName()
    {
        if(!isset($this->profile)) {
            return $this->email;
        } else {
            return implode(
                ' ',
                [
                    $this->firstName,
                    $this->lastName,
                    '(' . $this->email . ')'
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
        return Project::find()->notClosed()->userCan(Permission::PERMISSION_READ);
    }

    public function getOwnedProjects() {
        return $this->hasMany(Project::class, ['owner_id' => 'id']);
    }

    public function getUserLists()
    {
        return UserList::find()->userCan(Permission::PERMISSION_READ);
    }

    public function getUsername()
    {
        return $this->email;
    }

    public function getIsAdmin()
    {
        return app()->authManager->checkAccess($this->id, 'admin')
        && app()->request->getQueryParam(self::NON_ADMIN_KEY) === null;
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
        $scenarios['connect'] = ['email'];
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