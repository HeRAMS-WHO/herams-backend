<?php

declare(strict_types=1);

namespace prime\models\forms;

use Carbon\Carbon;
use herams\common\domain\permission\ProposedGrant;
use herams\common\domain\user\User;
use herams\common\models\ActiveRecord;
use herams\common\models\Permission;
use herams\common\models\Workspace;
use kartik\builder\Form;
use kartik\select2\Select2;
use prime\components\ActiveForm;
use prime\exceptions\NoGrantablePermissions;
use prime\widgets\AgGrid\AgGrid;
use prime\widgets\FormButtonsWidget;
use SamIT\abac\AuthManager;
use SamIT\abac\interfaces\Resolver;
use SamIT\Yii2\UrlSigner\UrlSigner;
use yii\base\Model;
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
use function iter\mapWithKeys;
use function iter\values;

/**
 * Class Share
 * @package prime\models\forms
 */
class Share extends Model
{
    private array $permissionOptions = [];

    public array $userIdsAndEmails = [];

    public array $permissions = [];

    public string $confirmationMessage;

    public function __construct(
        private object $model,
        private AuthManager $abacManager,
        private Resolver $resolver,
        private IdentityInterface $currentUser,
        private MailerInterface $mailer,
        private UrlSigner $urlSigner,
        ?array $availablePermissions,
        private int $linkExpirationDays = 7
    ) {
        if ($model instanceof ActiveRecord && $model->getIsNewRecord()) {
            throw new \InvalidArgumentException('Model must not be new');
        }
        parent::__construct([]);
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

                    if (! $this->abacManager->check($this->currentUser, $grant, Permission::PERMISSION_CREATE)) {
                        throw new \RuntimeException('You are not allowed to create this grant');
                    }
                }

                $subject = $this->resolver->fromSubject($this->model);
                $invitationRoute = [
                    '/user/accept-invitation',
                    'email' => $emailAddress,
                    'subject' => $subject->getAuthName(),
                    'subjectId' => $subject->getId(),
                    'permissions' => implode(',', $this->permissions),
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
        return array_filter($this->userIdsAndEmails, fn ($value) => ! is_numeric($value));
    }

    public function getUsers(): ActiveQueryInterface
    {
        return User::find()->where([
            'id' => array_filter($this->userIdsAndEmails, fn ($value) => is_numeric($value)),
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
        $users = User::find()->andWhere([
            'id' => array_filter($this->userIdsAndEmails, 'is_numeric'),
        ])->select(['id', 'name', 'email'])->asArray()->indexBy('id')->all();

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
                                'url' => Url::to(['/api/user/index']),
                                'dataType' => 'json',
                                'data' => new JsExpression('(params) => ({q:params.term})'),
                                'delay' => 400,
                                'processResults' => new JsExpression(<<<JS
                                    (data) => ({
                                        results: data.map((userObject) => {
                                            return {
                                                id: userObject.id,
                                                text: userObject.email
                                            }
                                        })
                                    })
                                    

                                JS),
                            ],
                            'tags' => true,
                            'maintainOrder' => true,
                        ],
                    ],
                ],
                'permissions' => [
                    'label' => \Yii::t('app', 'Permissions'),
                    'type' => Form::INPUT_CHECKBOX_LIST,
                    'items' => $this->permissionOptions,
                ],
                FormButtonsWidget::embed([
                    'buttons' => [
                        [
                            'label' => \Yii::t('app', 'Add'),
                            'options' => [
                                'class' => ['btn', 'btn-primary'],

                            ],
                        ],
                    ],
                ]),
            ],
        ]);
    }

    public function renderTable(string $deleteAction = '/permission/delete')
    {
        $target = $this->resolver->fromSubject($this->model);
        $route = $this->model instanceof Workspace ? '/api/workspace/permissions' : '/api/project/permissions';
        return AgGrid::widget([
            'route' => [
                $route,
                'id' => $target->getId(),
            ],
            'columns' => [
                [

                    'headerName' => \Yii::t('app', 'Name'),
                    'field' => 'name',

                    //                    'cellRenderer' => new JsExpression('ToggleButtonRenderer'),
                    //                    'cellRendererParams' => [
                    //                        'endpoint' => \yii\helpers\Url::to(['/api/user/workspaces', 'id' => \Yii::$app->user->id], true),
                    //                'idField' => 'id'
                    //            'width'=> 100,
                    //            'suppressSizeToFit' => true,
                    //                    'comparator' => new JsExpression('(a, b) => a == b ? 0 : a ? 1: -1')
                ],
                [
                    'headerName' => \Yii::t('app', 'Email'),
                    'field' => 'email',
                ],
                ...values(mapWithKeys(fn (string $label, string $permission) => [
                    'headerName' => $label,
                    'cellRenderer' => new JsExpression('ToggleButtonRenderer'),
                    'filter' => new JsExpression('ToggleButtonFilter'),
                    'field' => "permissions.$permission",
                    'cellRendererParams' => [
                        'onIcon' => 'mdi-toggle-switch',
                        'offIcon' => 'mdi-toggle-switch-off',
                        'paramName' => 'source_id',
                        'endpoint' => Url::to(['/api/permission/create'], true),
                        'postData' => [
                            // This is hardcoded but should actually go through a resolver.
                            'source' => 'User',
                            'target' => $target->getAuthName(),
                            'target_id' => $target->getId(),
                            'permission' => $permission,
                        ],
                    ],
                ], $this->permissionOptions)),
            ],
        ]);
    }

    private function replaceExistingEmailsWithIds(): void
    {
        $replaces = User::find()
            ->andWhere([
                'email' => $this->getInviteEmailAddresses(),
            ])
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
                        $existValidator = \Yii::createObject(ExistValidator::class, [[
                            'targetClass' => User::class,
                            'targetAttribute' => 'id',
                        ]]);
                        $emailValidator = \Yii::createObject(EmailValidator::class);

                        if (is_numeric($value)) {
                            if (! $existValidator->validate($value)) {
                                $this->addError($attribute, \Yii::t('app', 'Invalid user.'));
                            }
                        } else {
                            $error = null;
                            if (! $emailValidator->validate($value, $error)) {
                                $this->addError($attribute, \Yii::t('app', '{value} is an invalid email address.', [
                                    'value' => $value,
                                ]));
                            }
                        }
                    },
                ],
                'stopOnFirstError' => false,
            ],
            [['permissions'],
                RangeValidator::class,
                'allowArray' => true,
                'range' => array_keys($this->permissionOptions),
            ],
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
