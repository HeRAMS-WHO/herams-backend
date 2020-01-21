<?php

namespace prime\models\forms;

use kartik\builder\Form;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use prime\helpers\ProposedGrant;
use prime\models\ActiveRecord;
use prime\models\ar\User;
use prime\models\permissions\Permission;
use SamIT\abac\AuthManager;
use SamIT\abac\interfaces\Resolver;
use yii\base\Model;
use yii\bootstrap\Html;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQueryInterface;
use yii\helpers\ArrayHelper;
use yii\validators\DefaultValueValidator;
use yii\validators\ExistValidator;
use yii\validators\RangeValidator;
use yii\validators\RequiredValidator;
use yii\web\IdentityInterface;

class Share extends Model {
    private $_permissionOptions = [];
    public $userIds;
    public $permissions;

    private $model;

    /** @var AuthManager */
    private $abacManager;
    /** @var IdentityInterface */
    private $currentUser;

    public function __construct(
        object $model,
        AuthManager $abacManager,
        IdentityInterface $identity,
        $config = []
    ) {
        if($model instanceof ActiveRecord && $model->getIsNewRecord()) {
            throw new \InvalidArgumentException('Model must not be new');
        }
        parent::__construct($config);
        $this->model = $model;
        $this->abacManager = $abacManager;
        $this->currentUser = $identity;
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

    public function setPermissionOptions(array $options)
    {
        $this->_permissionOptions = $options;
    }

    private function permissionOptions(): array
    {
        $permissions = empty($this->_permissionOptions) ? Permission::permissionLabels() : $this->_permissionOptions;
        // Add labels if needed.
        foreach($permissions as $key => $value) {
            if (is_numeric($key)) {
                unset($permissions[$key]);
                $permissions[$value] = Permission::permissionLabels()[$value] ?? $value;
            }
        }

        return array_filter($permissions, function(string $permission) {
            $grant = new ProposedGrant($this->currentUser, $this->model, $permission);
            return $this->abacManager->check($this->currentUser, $grant, Permission::PERMISSION_CREATE);
        }, ARRAY_FILTER_USE_KEY);
    }

    public function getUserOptions()
    {
        return ArrayHelper::map(User::find()->andWhere(['not', ['id' => app()->user->id]])->all(), 'id', 'name');
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
                        'data' => $this->userOptions,
                        'options' => [
                            'multiple' => true
                        ]
                    ]
                ],
                'permissions' => [
                    'label' => \Yii::t('app', 'Permissions'),
                    'type' => Form::INPUT_CHECKBOX_LIST,
                    'items' => $this->permissionOptions()
                ]
            ]
        ]);
    }

    public function renderTable(string $deleteAction = '/permission/delete')
    {
        return \kartik\grid\GridView::widget([
            'dataProvider' => new ActiveDataProvider([
                'query' => $this->model->getPermissions()
            ]),
            'columns' => [
                [
                    'label' => \Yii::t('app', 'User'),
                    'value' => function(Permission $model) {
                        /** @var Resolver $resolver */
                        $resolver = \Yii::$app->abacResolver;
                        $source = $resolver->toSubject($model->sourceAuthorizable());
                        return $source->displayField ?? 'Deleted user';
                    }
                ],
                'permissionLabel' => [
                    'attribute' => 'permissionLabel',
                    'value' => function(Permission $model) {
                        return $this->permissionOptions()[$model->permission]
                            ?? Permission::permissionLabels()[$model->permission]
                            ?? $model->permission;
                    }
                ],
                [
                    'class' => \kartik\grid\ActionColumn::class,
                    'template' => '{delete}',
                    'buttons' => [
                        'delete' => function($url, Permission $model, $key) use ($deleteAction) {
                            $grant = $model->getGrant();
                            if ($this->abacManager->check($this->currentUser, $grant, Permission::PERMISSION_DELETE)) {
                                /** @var Resolver $resolver */
                                $resolver = \Yii::$app->abacResolver;
                                $source = $resolver->toSubject($model->sourceAuthorizable());
                                $target = $resolver->toSubject($model->targetAuthorizable());
                                return Html::a(
                                    Html::icon('trash'),
                                    [
                                        $deleteAction,
                                        'id' => $model->id,
                                        'redirect' => \Yii::$app->request->url
                                    ],
                                    [
                                        'class' => 'text-danger',
                                        'data-method' => 'delete',
                                        'data-confirm' => \Yii::t('app',
                                            'Are you sure you want to stop sharing <strong>{modelName}</strong> with <strong>{userName}</strong>',
                                            [
                                                'modelName' => $target->displayField ?? "{$model->targetAuthorizable()->getAuthName()} ({$model->targetAuthorizable()->getId()})",
                                                'userName' => $source->displayField ?? 'Deleted user'
                                            ]),
                                        'title' => \Yii::t('app', 'Remove')
                                    ]
                                );
                            }
                        }
                    ]
                ]
            ]
        ]);
    }

    public function rules() {
        return [
            [['permissions', 'userIds'], RequiredValidator::class],
            [['userIds'], ExistValidator::class, 'targetClass' => User::class, 'targetAttribute' => 'id', 'allowArray' => true],
            [['userIds'], DefaultValueValidator::class, 'value' => []],
            [['permissions'], RangeValidator::class,  'allowArray' => true, 'range' => array_keys($this->permissionOptions())]
        ];
    }
}