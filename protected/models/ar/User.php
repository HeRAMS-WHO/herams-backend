<?php

namespace prime\models\ar;

use prime\models\ActiveRecord;
use prime\queries\FavoriteQuery;
use SamIT\abac\AuthManager;
use SamIT\abac\interfaces\Grant;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\validators\RangeValidator;
use yii\validators\RegularExpressionValidator;
use yii\validators\RequiredValidator;
use yii\validators\StringValidator;
use yii\validators\UniqueValidator;
use yii\web\IdentityInterface;
use yii\web\Linkable;
use function iter\apply;
use function iter\chain;

/**
 * Class User
 * @property string $name
 * @property string $email
 * @property int $id
 * @property string $password_hash
 * @property ?string $language
 * @property Favorite[] $favorites
 */
class User extends ActiveRecord implements IdentityInterface
{
    public const NAME_REGEX = '/^[\'\w\- ]+$/u';

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'timestamp' => [
                'class' => TimestampBehavior::class
            ]
        ]);
    }

    public function rules(): array
    {
        return [
            [['email', 'name'], RequiredValidator::class],
            ['email', UniqueValidator::class,
                'targetClass' => User::class,
                'targetAttribute' => 'email',
                'message' => \Yii::t('app', "Email already taken")
            ],
            ['name', StringValidator::class, 'max' => 50],
            ['name', RegularExpressionValidator::class, 'pattern' => self::NAME_REGEX],
            ['language', RangeValidator::class, 'range' => \Yii::$app->params['languages']]
        ];
    }

    public function beforeDelete(): bool
    {
        /**
         * @todo ?Move this into the manager (revokeAll)
         * @todo Move this to something decoupled & eventbased.
         */
        if (parent::beforeDelete()) {
            /** @var AuthManager $manager */
            $manager = \Yii::$app->abacManager;
            $subject = $manager->resolveSubject($this);
            $repository = $manager->getRepository();
            if (isset($subject)) {
                apply(static function (Grant $grant) use ($repository) {
                    $repository->revoke($grant);
                }, chain(
                    $repository->search($subject, null, null),
                    $repository->search(null, $subject, null)
                ));
            }
            return true;
        }
        return false;
    }

    public static function findIdentity($id)
    {
        return self::findOne(['id' => $id]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    public static function labels(): array
    {
        return array_merge(parent::labels(), [
            'name' => \Yii::t('app', 'Name'),
            'email' => \Yii::t('app', 'Email'),
            'password_hash' => \Yii::t('app', 'Password hash'),
            'language' => \Yii::t('app', 'Language'),
        ]);
    }


    public function getId()
    {
        return $this->getAttribute('id');
    }

    public function getAuthKey()
    {
        return null;
    }

    public function validateAuthKey($authKey): bool
    {
        return false;
    }

    public function setPassword($value): void
    {
        if (!empty($value)) {
            $this->password_hash = \Yii::$app->security->generatePasswordHash($value);
        }
    }

    public function updatePassword(string $newPassword): void
    {
        $this->setPassword($newPassword);
        if (!$this->update(true, ['password_hash'])) {
            throw new \RuntimeException(\Yii::t('app', 'Password update failed'));
        }
    }

    public function getFavorites(): FavoriteQuery
    {
        return $this->hasMany(Favorite::class, ['user_id' => 'id'])->inverseOf('user');
    }
}
