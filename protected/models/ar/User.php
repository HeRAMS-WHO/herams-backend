<?php 

namespace prime\models\ar;

use app\queries\WorkspaceQuery;
use prime\assets\IconBundle;
use prime\models\ActiveRecord;
use prime\models\permissions\Permission;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Url;
use yii\validators\DefaultValueValidator;
use yii\validators\RegularExpressionValidator;
use yii\validators\StringValidator;
use yii\validators\UniqueValidator;
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
class User extends ActiveRecord implements IdentityInterface {

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'timestamp' => [
                'class' => TimestampBehavior::class
            ]
        ]);
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
        return [
            ['email', UniqueValidator::class,
                'targetClass' => User::class,
                'targetAttribute' => 'email',
                'message' => \Yii::t('app', "Email already taken")
            ],
            ['name', StringValidator::class, 'max' => 50],
            ['name', RegularExpressionValidator::class, 'pattern' => '/^[\'\w\- ]+$/u'],
        ];
    }

    public static function getDb()
    {
        return ActiveRecord::getDb();
    }

    public function getIsAdmin()
    {
        throw new NotSupportedException('use proper permission checking');
    }


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
        return null;
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
        return '';
    }

    /**
     * @inheritDoc
     */
    public function validateAuthKey($authKey)
    {
        return false;
    }

    public function setPassword($value): void
    {
        if (!empty($value)) {
            $this->password_hash = \Yii::$app->security->generatePasswordHash($value);
        }
    }
}