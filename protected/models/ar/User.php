<?php 

namespace prime\models\ar;

use app\queries\WorkspaceQuery;
use prime\models\ActiveRecord;
use SamIT\abac\AuthManager;
use SamIT\abac\interfaces\Grant;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\validators\RegularExpressionValidator;
use yii\validators\RequiredValidator;
use yii\validators\StringValidator;
use yii\validators\UniqueValidator;
use yii\web\IdentityInterface;
use function iter\apply;
use function iter\chain;

/**
 * Class User
 * @property string $name
 * @property int $id
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

    public function rules()
    {
        return [
            [['email', 'name'], RequiredValidator::class],
            ['email', UniqueValidator::class,
                'targetClass' => User::class,
                'targetAttribute' => 'email',
                'message' => \Yii::t('app', "Email already taken")
            ],
            ['name', StringValidator::class, 'max' => 50],
            ['name', RegularExpressionValidator::class, 'pattern' => '/^[\'\w\- ]+$/u'],
        ];
    }

    public function beforeDelete(): bool
    {
        if (parent::beforeDelete()) {
            /** @var AuthManager $manager */
            $manager = \Yii::$app->abacManager;
            $subject = $manager->resolveSubject($this);
            $repository = $manager->getRepository();
            if (isset($subject)) {
                apply(static function(Grant $grant) use ($repository) { $repository->revoke($grant); }, chain(
                    $repository->search($subject, null, null),
                    $repository->search(null, $subject, null)
                ));
            }
            return true;
        }
        return false;
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
}