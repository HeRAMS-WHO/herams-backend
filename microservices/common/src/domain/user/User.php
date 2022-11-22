<?php

declare(strict_types=1);

namespace herams\common\domain\user;

use herams\common\domain\favorite\Favorite;
use herams\common\domain\favorite\FavoriteQuery;
use herams\common\enums\Language;
use herams\common\jobs\users\SyncNewsletterSubscriptionJob;
use herams\common\models\ActiveRecord;
use herams\common\validators\BackedEnumValidator;
use JCIT\jobqueue\interfaces\JobQueueInterface;
use SamIT\abac\AuthManager;
use SamIT\abac\interfaces\Grant;
use yii\behaviors\TimestampBehavior;
use yii\validators\BooleanValidator;
use yii\validators\DefaultValueValidator;
use yii\validators\RegularExpressionValidator;
use yii\validators\RequiredValidator;
use yii\validators\StringValidator;
use yii\validators\UniqueValidator;
use yii\web\IdentityInterface;
use function iter\apply;
use function iter\chain;

/**
 * Class User
 *
 * @property int $created_at
 * @property string $email
 * @property int $id
 * @property ?string $language
 * @property ?int $last_login_at
 * @property string $name
 * @property bool $newsletter_subscription
 * @property string $password_hash
 * @property int $updated_at
 *
 * @property Favorite[] $favorites
 */
class User extends ActiveRecord implements IdentityInterface
{
    public const NAME_REGEX = '/^[\'\w\- ]+$/u';

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($insert || isset($changedAttributes['newsletter_subscription'])) {
            $jobQueue = \Yii::createObject(JobQueueInterface::class);
            $jobQueue->putJob(new SyncNewsletterSubscriptionJob($this->id, $insert));
        }
    }

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'timestamp' => [
                'class' => TimestampBehavior::class,
            ],
        ]);
    }

    public function rules(): array
    {
        return [
            [['email', 'name'], RequiredValidator::class],
            [
                'email',
                UniqueValidator::class,
                'targetClass' => User::class,
                'targetAttribute' => 'email',
                'message' => \Yii::t('app', "Email already taken"),
            ],
            [
                'name',
                StringValidator::class,
                'max' => 50,
            ],
            [
                'name',
                RegularExpressionValidator::class,
                'pattern' => self::NAME_REGEX,
            ],
            [
                'language',
                BackedEnumValidator::class,
                'example' => Language::enUS,
            ],
            [['newsletter_subscription'],
                DefaultValueValidator::class,
                'value' => false,
            ],
            [['newsletter_subscription'], BooleanValidator::class],
        ];
    }

    public function getPreferredLanguage(): null|Language
    {
        return ! empty($this->language) ? Language::tryFrom($this->language) : null;
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
            apply(static function (Grant $grant) use ($repository) {
                $repository->revoke($grant);
            }, chain(
                $repository->search($subject, null, null),
                $repository->search(null, $subject, null)
            ));
            return true;
        }
        return false;
    }

    public static function findIdentity($id)
    {
        return self::findOne([
            'id' => $id,
        ]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    public static function labels(): array
    {
        return array_merge(parent::labels(), [
            'email' => \Yii::t('app', 'Email'),
            'language' => \Yii::t('app', 'Language'),
            'name' => \Yii::t('app', 'Name'),
            'newsletter_subscription' => \Yii::t('app', 'Newsletter subscription'),
            'password_hash' => \Yii::t('app', 'Password hash'),
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
        if (! empty($value)) {
            $this->password_hash = \Yii::$app->security->generatePasswordHash($value);
        }
    }

    public function updatePassword(string $newPassword): void
    {
        $this->setPassword($newPassword);
        if (! $this->update(true, ['password_hash'])) {
            throw new \RuntimeException(\Yii::t('app', 'Password update failed'));
        }
    }

    public function getFavorites(): FavoriteQuery
    {
        return $this->hasMany(Favorite::class, [
            'user_id' => 'id',
        ])->inverseOf('user');
    }

    public function fields()
    {
        $result = parent::fields();
        unset($result['password_hash']);
        return $result;
    }
}
