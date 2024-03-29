<?php

declare(strict_types=1);

namespace herams\common\domain\user;

use Carbon\Carbon;
use herams\common\domain\favorite\Favorite;
use herams\common\domain\favorite\FavoriteQuery;
use herams\common\enums\Language;
use herams\common\jobs\users\SyncNewsletterSubscriptionJob;
use herams\common\models\Permission;
use herams\common\models\Project;
use herams\common\models\Role;
use herams\common\models\RolePermission;
use herams\common\models\UserRole;
use herams\common\models\Workspace;
use herams\common\traits\JsonBase64EncoderTrait;
use herams\common\validators\BackedEnumValidator;
use JCIT\jobqueue\interfaces\JobQueueInterface;
use SamIT\abac\AuthManager;
use SamIT\abac\interfaces\Grant;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\Query;
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
 * @property string $last_login_date
 * @property int $updated_at
 *
 * @property Favorite[] $favorites
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    use JsonBase64EncoderTrait;

    public const NAME_REGEX = '/^[\'\w\- ]+$/u';

    private bool $excludeDateTimeFields = false;

    private array $selectedFields = [];

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
                'updatedAtAttribute' => 'last_modified_date',
                'createdAtAttribute' => 'created_date',
                'value' => Carbon::now('CET')
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
                'example' => Language::en,
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

    public function getCreator(): ActiveQuery
    {
        return $this->hasOne(User::class, [
            'id' => 'created_by',
        ])->alias('creator');
    }

    public function getUpdater(): ActiveQuery
    {
        return $this->hasOne(User::class, [
            'id' => 'last_modified_by',
        ])->alias('updater');
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

    public function fields(): array
    {
        $result = parent::fields();

        if ($this->excludeDateTimeFields) {
            unset($result['created_at'], $result['updated_at']);
        }

        // Only keep fields present in selectedFields if it's set
        if ($this->selectedFields && is_array($this->selectedFields)) {
            $result = array_intersect_key($result, array_flip($this->selectedFields));
        }

        unset($result['password_hash']);
        return $result;
    }

    public function setExcludeDateTimeFields(bool $exclude = true)
    {
        $this->excludeDateTimeFields = $exclude;
    }

    public function setOnlyFields(array $selectedFields = [])
    {
        $this->selectedFields = $selectedFields;
    }

    /**
     * @return bool
     */
    public function updateLastLoginDate(): bool
    {
        $this->setAttribute('last_login_date', Carbon::now('CET'));

        return $this->save();
    }


    /**
     * @param $userId
     * @return array
     */
    public function calculatePermissions($userId = null): array
    {
        $userId = $userId ?? $this->getId();
        $permissions = [];
        $results = (new Query())
            ->select("ur.user_id as UserId")->distinct()
            ->addSelect(["rp.permission_code", "ur.target", "case when ur.target = 'project' then p1.id
                    when ur.target = 'workspace' then p2.id
                        end as ProjectId,
                    if (ur.target = 'workspace', w.id, null) as WorkspaceId"
            ])
            ->from(UserRole::tableName() . ' ur')
            ->leftJoin(Role::tableName() . ' r', 'ur.role_id = r.id')
            ->leftJoin(RolePermission::tableName() . ' rp', 'ur.role_id = rp.role_id')
            ->leftJoin(Project::tableName() . ' p1', "ur.target = 'project' and ur.target_id = p1.id")
            ->leftJoin(Workspace::tableName() . ' w', "ur.target = 'workspace' and ur.target_id = w.id")
            ->leftJoin(Project::tableName() . ' p2', "ur.target = 'workspace' and w.project_id = p2.id")
            ->where(['ur.user_id' => $userId])
            ->orderBy('rp.permission_code')
            ->all();

        foreach ($results as $result) {
            if (Permission::GLOBAL_TARGET == $result['target']) {
                $permissions[Permission::GLOBAL_TARGET][] = $result['permission_code'];
            } else if (Permission::PROJECT_TARGET == $result['target']) {
                $permissions[Permission::PROJECT_TARGET][$result['ProjectId']][] = $result['permission_code'];
            } else if (Permission::WORKSPACE_TARGET == $result['target']) {
                $permissions[Permission::WORKSPACE_TARGET][$result['ProjectId']][$result['WorkspaceId']][] = $result['permission_code'];
            }
        }

        return $permissions;
    }

    /**
     * @param array $permissions
     * @return void
     */
    public function setPermissions(array $permissions = []): void
    {
        \Yii::$app->session->set('permissions', json_encode($permissions, JSON_UNESCAPED_UNICODE));
    }

    /**
     * @return mixed
     */
    public function getPermissions(): mixed
    {
        return json_decode(\Yii::$app->session->get('permissions'), true);
    }
}
