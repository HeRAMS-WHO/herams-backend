<?php

namespace prime\models\forms;

use kartik\builder\Form;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use prime\models\ActiveRecord;
use prime\models\ar\User;
use prime\models\permissions\Permission;
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

    /** @var CheckAccessInterface */
    private $accessChecker;
    /** @var int */
    private $userId;

    public function __construct(
        ActiveRecord $model,
        CheckAccessInterface $accessChecker,
        IdentityInterface $identity
    ) {
        if($model->getIsNewRecord()) {
            throw new \InvalidArgumentException('Model must not be new');
        }
        parent::__construct([]);
        $this->model = $model;
        $this->accessChecker = $accessChecker;
        $this->userId = $identity->getId();

    }

    public function attributeLabels()
    {
        return [
            'userIds' => \Yii::t('app', 'Users'),
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
                        Permission::grant($user, $this->model, $permission);
                    }
                }
                $transaction->commit();
                return true;
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
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
        $permissions = empty($this->_permissions) ? Permission::permissionLabels() : $this->_permissions;

        // Add labels if needed.
        foreach($permissions as $key => $value) {
            if (is_numeric($key)) {
                unset($permissions[$key]);
                $permissions[$value] = Permission::permissionLabels()[$value];
            }
        }
        return array_filter($permissions, function($key) {
            return $this->accessChecker->checkAccess($this->userId, $key, $this->model);
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
                'permissionLabel' => [
                    'attribute' => 'permissionLabel',
                    'value' => function(Permission $model) {
                        return $this->getPermissionOptions()[$model->permission];
                    }
                ],
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