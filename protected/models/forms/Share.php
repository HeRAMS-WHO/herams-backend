<?php

declare(strict_types=1);

namespace prime\models\forms;

use app\components\ActiveForm;
use Carbon\Carbon;
use kartik\builder\Form;
use kartik\select2\Select2;
use prime\exceptions\NoGrantablePermissions;
use prime\helpers\ProposedGrant;
use prime\models\ActiveRecord;
use prime\models\ar\Permission;
use prime\models\ar\User;
use prime\widgets\FormButtonsWidget;
use prime\widgets\PermissionColumn\PermissionColumn;
use SamIT\abac\AuthManager;
use SamIT\abac\interfaces\Resolver;
use SamIT\Yii2\UrlSigner\UrlSigner;
use yii\base\Model;
use yii\data\ArrayDataProvider;
use yii\db\ActiveQueryInterface;
use yii\helpers\Url;
use yii\mail\MailerInterface;
use yii\validators\EachValidator;
use yii\validators\EmailValidator;
use yii\validators\ExistValidator;
use yii\validators\InlineValidator;
use yii\validators\RangeValidator;
use yii\validators\RequiredValidator;
use yii\web\IdentityInterface;
use yii\web\JsExpression;

/**
 * Class Share
 * @package prime\models\forms
 */
class Share extends Model
{
    private array $permissionOptions = [];
    private int $linkExpirationDays;
    public array $userIdsAndEmails = [];
    public array $permissions = [];

    private object $model;

    public $confirmationMessage;

    private AuthManager $abacManager;
    private IdentityInterface $currentUser;
    private MailerInterface $mailer;
    private Resolver $resolver;
    private UrlSigner $urlSigner;

    public function __construct(
        object $model,
        AuthManager $abacManager,
        Resolver $resolver,
        IdentityInterface $identity,
        MailerInterface $mailer,
        UrlSigner $urlSigner,
        ?array $availablePermissions,
        int $linkExpirationDays = 7
    ) {
        if ($model instanceof ActiveRecord && $model->getIsNewRecord()) {
            throw new \InvalidArgumentException('Model must not be new');
        }
        parent::__construct([]);
        $this->abacManager = $abacManager;
        $this->currentUser = $identity;
        $this->linkExpirationDays = $linkExpirationDays;
        $this->mailer = $mailer;
        $this->model = $model;
        $this->resolver = $resolver;
        $this->urlSigner = $urlSigner;
        $this->setPermissionOptions($availablePermissions);
        if (empty($this->permissionOptions)) {
            throw new NoGrantablePermissions();
        }
    }

    public function attributeLabels()
    {
        return [
            'userIdsAndEmails' => \Yii::t('app', 'Users'),
        ];
    }

    public function createRecords(): void
    {
        if ($this->validate()) {
            // Grant permissions for existing users
            /** @var User $user */
            foreach ($this->getUsers()->all() as $user) {
                foreach ($this->permissions as $permission) {
                    $grant = new ProposedGrant($user, $this->model, $permission);

                    if ($this->abacManager->check($this->currentUser, $grant, Permission::PERMISSION_CREATE)) {
                        $this->abacManager->grant($user, $this->model, $permission);
                    } else {
                        throw new \RuntimeException('You are not allowed to create this grant');
                    }
                }
            }

            // Invite users for which no account exists
            foreach ($this->getInviteEmailAddresses() as $emailAddress) {
                foreach ($this->permissions as $permission) {
                    $grant = new ProposedGrant(new User(), $this->model, $permission);

                    if (!$this->abacManager->check($this->currentUser, $grant, Permission::PERMISSION_CREATE)) {
                        throw new \RuntimeException('You are not allowed to create this grant');
                    }
                }

                $subject = $this->resolver->fromSubject($this->model);
                $invitationRoute = [
                    '/user/accept-invitation',
                    'email' => $emailAddress,
                    'subject' => $subject->getAuthName(),
                    'subjectId' => $subject->getId(),
                    'permissions' => implode(',', $this->permissions)
                ];
                $this->urlSigner->signParams($invitationRoute, false, Carbon::now()->addDays($this->linkExpirationDays));

                $this->mailer->compose(
                    'invitation',
                    [
                        'user' => $this->currentUser,
                        'invitationRoute' => $invitationRoute,
                        'linkExpirationDays' => $this->linkExpirationDays,
                    ]
                )
                    ->setTo($emailAddress)
                    ->send()
                ;
            }
        }
    }

    public function getInviteEmailAddresses(): array
    {
        return array_filter($this->userIdsAndEmails, fn($value) => !is_numeric($value));
    }

    public function getUsers(): ActiveQueryInterface
    {
        return User::find()->where([
            'id' => array_filter($this->userIdsAndEmails, fn($value) => is_numeric($value)),
        ]);
    }

    public function load($data, $formName = null): bool
    {
        $result = parent::load($data, $formName);
        $this->replaceExistingEmailsWithIds();
        return $result;
    }

    public function renderForm(ActiveForm $form)
    {
        $initialValue = [];
        $users = User::find()->andWhere(['id' => array_filter($this->userIdsAndEmails, 'is_numeric')])->select(['id', 'name', 'email'])->asArray()->indexBy('id')->all();

        foreach ($this->userIdsAndEmails as $idOrEmail) {
            if (is_numeric($idOrEmail)) {
                $initialValue[$idOrEmail] = "{$users[$idOrEmail]['name']} ({$users[$idOrEmail]['email']})";
            } else {
                $initialValue[$idOrEmail] = $idOrEmail;
            }
        }

        return Form::widget([
            'form' => $form,
            'model' => $this,
            'columns' => 1,
            "attributes" => [
                'userIdsAndEmails' => [
                    'type' => Form::INPUT_WIDGET,
                    'widgetClass' => Select2::class,
                    'options' => [
                        'data' => $initialValue,
                        'options' => [
                            'multiple' => true,
                        ],
                        'pluginOptions' => [
                            'ajax' => [
                                'url' => Url::to(['user/select-2']),
                                'dataType' => 'json',
                                'data' => new JsExpression('function(params){ return {q:params.term};}'),
                                'delay' => 400,
                            ],
                            'tags' => true,
                            'maintainOrder' => true,
                        ]
                    ]
                ],
                'permissions' => [
                    'label' => \Yii::t('app', 'Permissions'),
                    'type' => Form::INPUT_CHECKBOX_LIST,
                    'items' => $this->permissionOptions
                ],
                FormButtonsWidget::embed([
                    'buttons' => [
                        ['label' => \Yii::t('app', 'Add'), 'options' => ['class' => ['btn', 'btn-primary']]]
                    ]
                ])
            ]
        ]);
    }

    public function renderTable(string $deleteAction = '/permission/delete')
    {
        $target = $this->resolver->fromSubject($this->model);
        $permissions = [];
        $columns = [];
        foreach ($this->permissionOptions as $permission => $label) {
            $columns[] = [
                'class' => PermissionColumn::class,
                'permission' => $permission,
                'target' => $target,
                'label' => $label,
                'attribute' => "permissions.{$permission}"
            ];
        }

        /** @var \prime\models\ar\Permission $permission */
        foreach ($this->model->getPermissions()->each() as $permission) {
            $source = $permission->sourceAuthorizable();
            $key = $source->getAuthName() . '|' . $source->getId();
            if (!isset($permissions[$key])) {
                $permissions[$key] = [
                   'source' => $source,
                   'user' => $this->resolver->toSubject($source)->displayField ?? \Yii::t('app', 'Deleted user'),
                   'permissions' => []
                ];
            }
            $permissions[$key]['permissions'][$permission->permission] = $permission;
        }
        return \yii\grid\GridView::widget([
            'dataProvider' => new ArrayDataProvider([
                'allModels' => $permissions
            ]),
            'columns' => array_merge([
                [
                    'attribute' => 'user',
                    'label' => \Yii::t('app', 'User')
                ],
//                [
//                    'class' => \kartik\grid\ActionColumn::class,
//                    'template' => '{delete}',
//                    'buttons' => [
//                        'delete' => function($url, Permission $model, $key) use ($deleteAction) {
//                            /** @var Resolver $resolver */
//                            $resolver = \Yii::$app->abacResolver;
//                            $source = $resolver->toSubject($model->sourceAuthorizable());
//                            $target = $resolver->toSubject($model->targetAuthorizable());
//                            if (!isset($source, $target)) {
//                                return '';
//                            }
//                            $grant = new ProposedGrant($source, $target, $model->permission);
//                            if ($this->abacManager->check($this->currentUser, $grant, Permission::PERMISSION_DELETE)) {
//                                return Html::a(
//                                    Html::icon('trash'),
//                                    [
//                                        $deleteAction,
//                                        'id' => $model->id,
//                                        'redirect' => \Yii::$app->request->url
//                                    ],
//                                    [
//                                        'class' => 'text-danger',
//                                        'data-method' => 'delete',
//                                        'data-confirm' => $this->confirmationMessage ?? \Yii::t('app',
//                                            'Are you sure you want to stop sharing <strong>{modelName}</strong> with <strong>{userName}</strong>',
//                                            [
//                                                'modelName' => $target->displayField ?? "{$model->targetAuthorizable()->getAuthName()} ({$model->targetAuthorizable()->getId()})",
//                                                'userName' => $source->displayField ?? 'Deleted user'
//                                            ]),
//                                        'title' => \Yii::t('app', 'Remove')
//                                    ]
//                                );
//                            }
//                        }
//                    ]
//                ]
            ], $columns)
        ]);
    }

    private function replaceExistingEmailsWithIds(): void
    {
        $replaces = User::find()
            ->andWhere(['email' => $this->getInviteEmailAddresses()])
            ->indexBy('email')
            ->select('id')
            ->column();

        foreach ($this->userIdsAndEmails as $key => $idOrEmail) {
            if (is_numeric($idOrEmail)) {
                continue;
            }

            if (isset($replaces[$idOrEmail])) {
                $this->userIdsAndEmails[$key] = $replaces[$idOrEmail];
            }
        }
    }

    public function rules(): array
    {
        return [
            [['permissions', 'userIdsAndEmails'], RequiredValidator::class],
            [
                ['userIdsAndEmails'],
                EachValidator::class,
                'rule' => [
                    function ($attribute, $params, InlineValidator $validator, $value) {
                        $existValidator = \Yii::createObject(ExistValidator::class, [['targetClass' => User::class, 'targetAttribute' => 'id']]);
                        $emailValidator = \Yii::createObject(EmailValidator::class);

                        if (is_numeric($value)) {
                            if (!$existValidator->validate($value)) {
                                $this->addError($attribute, \Yii::t('app', 'Invalid user.'));
                            }
                        } else {
                            $error = null;
                            if (!$emailValidator->validate($value, $error)) {
                                $this->addError($attribute, \Yii::t('app', '{value} is an invalid email address.', ['value' => $value]));
                            }
                        }
                    }
                ],
                'stopOnFirstError' => false,
            ],
            [['permissions'], RangeValidator::class,  'allowArray' => true, 'range' => array_keys($this->permissionOptions)]
        ];
    }

    private function setPermissionOptions(?array $options)
    {
        // Add labels if needed.
        foreach ($options ?? Permission::permissionLabels() as $permission => $label) {
            if (is_numeric($permission)) {
                $permission = $label;
                $label = Permission::permissionLabels()[$permission] ?? $permission;
            }
            $grant = new ProposedGrant($this->currentUser, $this->model, $permission);
            if ($this->abacManager->check($this->currentUser, $grant, Permission::PERMISSION_CREATE)) {
                $this->permissionOptions[$permission] = $label;
            }
        }
    }
}
