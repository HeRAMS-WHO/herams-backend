<?php 

namespace prime\models\ar;

use app\queries\WorkspaceQuery;
use prime\assets\IconBundle;
use prime\models\ActiveRecord;
use prime\models\permissions\Permission;
use yii\base\NotSupportedException;
use yii\helpers\Url;
use yii\web\IdentityInterface;

/**
 * Class User
 * @property Profile $profile
 * @property string $firstName
 * @property string $lastName
 * @property string $organization
 * @property string $office
 * @property string $country
 * @property string $gravatarUrl
 * @property string $name
 */
class User extends \dektrium\user\models\User implements IdentityInterface {

    public $last_login_at;
    public function getUserName()
    {
        return null;
    }

    public function getFirstName(): ?string
    {
        return $this->profile->first_name ?? null;
    }

    public function getLastName(): ?string
    {
        return $this->profile->last_name ?? null;
    }

    /**
     * The project find function only returns projects a user has at least read access to
     */
    public function getProjects(): WorkspaceQuery
    {
        return Workspace::find()->notClosed()->userCan(Permission::PERMISSION_READ);
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

    public function setPassword(string $password): void
    {
        $this->password_hash = password_hash($password, PASSWORD_DEFAULT);
    }

    public static function getDb()
    {
        return ActiveRecord::getDb();
    }

    public function getIsAdmin()
    {
        throw new NotSupportedException('use proper permission checking');
    }


}