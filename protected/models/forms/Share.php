<?php

namespace prime\models\forms;

use app\components\ActiveForm;
use kartik\builder\Form;
use kartik\grid\GridView;
use kartik\select2\Select2;
use prime\exceptions\NoGrantablePermissions;
use prime\helpers\ProposedGrant;
use prime\models\ActiveRecord;
use prime\models\ar\Permission;
use prime\models\ar\User;
use prime\widgets\PermissionColumn\PermissionColumn;
use SamIT\abac\AuthManager;
use SamIT\abac\interfaces\Resolver;
use yii\base\Model;
use yii\data\ArrayDataProvider;
use yii\db\ActiveQueryInterface;
use yii\helpers\ArrayHelper;
use yii\validators\ExistValidator;
use yii\validators\RangeValidator;
use yii\validators\RequiredValidator;
use yii\web\IdentityInterface;

/**
 * Class Share
 * @package prime\models\forms
 */
class Share extends Model
{
    private $permissionOptions = [];
    public $userIds = [];
    public $permissions = [];

    private $model;

    public $confirmationMessage;

    /** @var AuthManager */
    private $abacManager;
    /** @var IdentityInterface */
    private $currentUser;

    public function __construct(
        object $model,
        AuthManager $abacManager,
        IdentityInterface $identity,
        ?array $availablePermissions
    ) {
        if ($model instanceof ActiveRecord && $model->getIsNewRecord()) {
            throw new \InvalidArgumentException('Model must not be new');
        }
        parent::__construct([]);
        $this->model = $model;
        $this->abacManager = $abacManager;
        $this->currentUser = $identity;
        $this->setPermissionOptions($availablePermissions);
        if (empty($this->permissionOptions)) {
            throw new NoGrantablePermissions();
        }
    }

    public function attributeLabels()
    {
        return [
            'userIds' => \Yii::t('app', 'Users'),
        ];
    }

    public function createRecords(): void
    {
        if ($this->validate()) {
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
        }
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

    public function getUserOptions()
    {
        return ArrayHelper::map(User::find()->andWhere(['not', ['id' => app()->user->id]])->all(), 'id', function (User $user) {
            return "{$user->name} ({$user->email})";
        });
    }

    public function getUsers(): ActiveQueryInterface
    {
        return User::find()->where([
            'id' => $this->userIds
        ]);
    }

    public function renderForm(ActiveForm $form)
    {
        return Form::widget([
            'form' => $form,
            'model' => $this,
            'columns' => 1,
            "attributes" => [
                'userIds' => [

                    'type' => Form::INPUT_WIDGET,
                    'widgetClass' => Select2::class,
                    'options' => [
                        'data' => $this->getUserOptions(),
                        'options' => [
                            'multiple' => true
                        ]
                    ]
                ],
                'permissions' => [
                    'label' => \Yii::t('app', 'Permissions'),
                    'type' => Form::INPUT_CHECKBOX_LIST,
                    'items' => $this->permissionOptions
                ]
            ]
        ]);
    }

    public function renderTable(string $deleteAction = '/permission/delete')
    {
        /** @var Resolver $resolver */
        $resolver = \Yii::$app->abacResolver;
        $target = $resolver->fromSubject($this->model);
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
                   'user' => $resolver->toSubject($source)->displayField ?? \Yii::t('app', 'Deleted user'),
                   'permissions' => []
                ];
            }
            $permissions[$key]['permissions'][$permission->permission] = $permission;
        }
        return GridView::widget([
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

    public function rules()
    {
        return [
            [['permissions', 'userIds'], RequiredValidator::class],
            [['userIds'], ExistValidator::class, 'targetClass' => User::class, 'targetAttribute' => 'id', 'allowArray' => true],
            [['permissions'], RangeValidator::class,  'allowArray' => true, 'range' => array_keys($this->permissionOptions)]
        ];
    }
}
