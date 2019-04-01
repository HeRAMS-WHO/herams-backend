<?php

namespace prime\models\forms;

use function iter\rewindable\filter;
use kartik\builder\Form;
use prime\models\ActiveRecord;
use prime\models\ar\User;
use prime\models\permissions\Permission;
use yii\base\Model;
use yii\bootstrap\Html;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\validators\DefaultValueValidator;
use yii\validators\ExistValidator;
use yii\validators\RangeValidator;
use yii\validators\RequiredValidator;

class Share extends Model {
    protected $_permissions = [];
    public $userIds;
    public $userListIds;
    public $permission;
    protected $excludeUserIds;
    protected $model;

    public function __construct(ActiveRecord $model, array $excludeUserIds = [], array $config = [])
    {
        parent::__construct($config);
        $this->model = $model;
        $this->excludeUserIds = $excludeUserIds;
        if($this->model->getIsNewRecord()) {
            throw new \InvalidArgumentException('Model must not be new');
        }
    }

    public function attributeLabels()
    {
        return [
            'userIds' => \Yii::t('app', 'Users'),
        ];
    }

    public function attributeHints()
    {
        return [
            'permission' => \Yii::t('app', 'You are only allowed to grant permissions that you have'),
        ];
    }


    /**
     * @return bool
     */
    public function createRecords()
    {
        if($this->validate()) {
            $transaction = app()->db->beginTransaction();
            try {
                foreach ($this->getUsers()->all() as $user) {
                    foreach($this->permission as $permission) {
                        if (!Permission::grant($user, $this->model, $permission)) {
                            throw new \Exception("Failed to grant permission");
                        }
                    }
                }
                $transaction->commit();
                return true;
            } catch (\Exception $e) {
                $transaction->rollBack();
            }

        }
        return false;
    }

    public function setPermissions(array $options)
    {
        $this->_permissions = $options;
    }

    public function getPermissionOptions(): array
    {
        $permissions = !empty($this->_permissions) ? array_intersect_key(Permission::permissionLabels(), array_flip($this->_permissions)) : Permission::permissionLabels();
        // Filter for current user.
        foreach($permissions as $key => $label) {
            if (!app()->user->can($key, $this->model)) {
                unset($permissions[$key]);
            }
        }
        return $permissions;
    }

    public function getUserOptions()
    {
        return ArrayHelper::map(User::find()->andWhere(['not', ['id' => app()->user->id]])->all(), 'id', 'name');
    }

    public function getUsers()
    {
        return User::find()->where([
            'id' => $this->userIds
        ]);
    }

    public function renderForm(\kartik\widgets\ActiveForm $form)
    {
        return Form::widget([
            'form' => $form,
            'model' => $this,
            'columns' => 1,
            "attributes" => [
                'userIds' => [
                    'type' => Form::INPUT_WIDGET,
                    'widgetClass' => \kartik\widgets\Select2::class,
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

    public function renderTable($deleteAction = '/permission/delete')
    {
        return \kartik\grid\GridView::widget([
            'dataProvider' => new ActiveDataProvider([
                'query' => $this->model->getPermissions()
            ]),
            'columns' => [
                [
                    'label' => \Yii::t('app', 'User'),
                    'value' => function($model){

                        return $model->sourceObject->name;
                    }
                ],
                'permissionLabel',
                [
                    'class' => \kartik\grid\ActionColumn::class,
                    'template' => '{delete}',
                    'buttons' => [
                        'delete' => function($url, $model, $key) use ($deleteAction) {
                            if($deleteAction == '') {
                                return '';
                            }
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
                                        'userName' => $model->sourceObject->name
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