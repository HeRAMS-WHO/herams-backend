<?php

namespace prime\models\forms;

use kartik\builder\Form;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use prime\models\ActiveRecord;
use prime\models\ar\User;
use prime\models\permissions\Permission;
use SamIT\abac\AuthManager;
use yii\base\Model;
use yii\bootstrap\Html;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQueryInterface;
use yii\helpers\ArrayHelper;
use yii\rbac\CheckAccessInterface;
use yii\validators\DefaultValueValidator;
use yii\validators\ExistValidator;
use yii\validators\RangeValidator;
use yii\validators\RequiredValidator;
use yii\web\IdentityInterface;

class Share extends Model {
    protected $_permissions = [];
    public $userIds;
    public $permission;
    protected $model;

    /** @var AuthManager */
    private $abacManager;
    /** @var IdentityInterface */
    private $user;

    public function __construct(
        ActiveRecord $model,
        AuthManager $abacManager,
        IdentityInterface $identity,
        $config = []
    ) {
        if($model->getIsNewRecord()) {
            throw new \InvalidArgumentException('Model must not be new');
        }
        parent::__construct($config);
        $this->model = $model;
        $this->abacManager = $abacManager;
        $this->user = $identity;
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
            foreach ($this->getUsers()->all() as $user) {
                foreach ($this->permission as $permission) {
                    if ($this->abacManager->check($this->user, $this->model, $permission)) {
                        $this->abacManager->grant($user, $this->model, $permission);
                    }

                }
            }
        }
    }

    public function setPermissions(array $options)
    {
        $this->_permissions = $options;
    }

    public function getPermissionOptions(): array
    {
        $permissions = empty($this->_permissions) ? Permission::permissionLabels() : $this->_permissions;

        // Add labels if needed.
        foreach($permissions as $key => $value) {
            if (is_numeric($key)) {
                unset($permissions[$key]);
                $permissions[$value] = Permission::permissionLabels()[$value];
            }
        }
        return array_filter($permissions, function(string $permission) {
            return $this->abacManager->check($this->user, $this->model, $permission);
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
                'permission' => [
                    'label' => \Yii::t('app', 'Permission'),
                    'type' => Form::INPUT_CHECKBOX_LIST,
                    'items' => $this->permissionOptions
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
                    'value' => function($model){
                        return isset($model->sourceObject) ? $model->sourceObject->name : 'Deleted user';
                    }
                ],
                'permissionLabel' => [
                    'attribute' => 'permissionLabel',
                    'value' => function(Permission $model) {
                        return $this->getPermissionOptions()[$model->permission] ?? $model->permission;
                    }
                ],
                [
                    'class' => \kartik\grid\ActionColumn::class,
                    'template' => '{delete}',
                    'buttons' => [
                        'delete' => function($url, $model, $key) use ($deleteAction) {
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
                                    'data-confirm' => \Yii::t('app', 'Are you sure you want to stop sharing <strong>{modelName}</strong> with <strong>{userName}</strong>', [
                                        'modelName' => $model->targetObject->displayField,
                                        'userName' => isset($model->sourceObject) ? $model->sourceObject->name : 'Deleted user'
                                    ]),
                                    'title' => \Yii::t('app', 'Remove')
                                ]
                            );
                        }
                    ]
                ]
            ]
        ]);
    }

    public function rules() {
        return [
            [['permission', 'userIds'], RequiredValidator::class],
            [['userIds'], ExistValidator::class, 'targetClass' => User::class, 'targetAttribute' => 'id', 'allowArray' => true],
            [['userIds'], DefaultValueValidator::class, 'value' => []],
            [['permission'], RangeValidator::class,  'allowArray' => true, 'range' => array_keys($this->getPermissionOptions())]
        ];
    }
}