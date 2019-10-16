<?php 

namespace prime\models\ar;

use app\queries\WorkspaceQuery;
use prime\assets\IconBundle;
use prime\models\permissions\Permission;
use yii\helpers\Url;

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
class User extends \dektrium\user\models\User {

    public $last_login_at;
    const NON_ADMIN_KEY = 'safe';

    public function getUserName()
    {
        return null;
    }

    public function getGravatarUrl ($size = 256)
    {
        return "//s.gravatar.com/avatar/" . md5(strtolower(trim($this->email))) . '?'. http_build_query([
            's' => $size,
//            'default' =>
//            \Yii::$app->request->hostInfo . \Yii::$app->assetManager->getAssetUrl(IconBundle::register(\Yii::$app->view), 'fonts/svg/profile.svg')
        ]);
    }

    public function getFirstName(): ?string
    {
        return $this->profile->first_name ?? null;
    }

    public function getLastName(): ?string
    {
        return $this->profile->last_name ?? null;
    }

    public function getName(): string
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
     */
    public function getProjects(): WorkspaceQuery
    {
        return Workspace::find()->notClosed()->userCan(Permission::PERMISSION_READ);
    }

    public function getOwnedProjects(): WorkspaceQuery
    {
        return $this->hasMany(Workspace::class, ['owner_id' => 'id']);
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

}