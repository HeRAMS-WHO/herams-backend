<?php

/**
 * @var \yii\web\View $this
 * @var \prime\models\ar\UserList $model
 */

$this->title = Yii::t('app', 'User list {name}', ['name' => $model->name]);

$this->params['subMenu'] = [
    'items' => [
        [
            'label' => \Yii::t('app', 'Delete'),
            'url' => ['/user-lists/delete', 'id' => $model->id],
            'linkOptions' => [
                'data-confirm' => \Yii::t('app', 'Are you sure you want to delete list <strong>{name}</strong>?', ['name' => $model->name]),
                'data-method' => 'delete'
            ],
            'visible' => $model->userCan(\prime\models\permissions\Permission::PERMISSION_SHARE)
        ],
        [
            'label' => \Yii::t('app', 'Edit'),
            'url' => ['/user-lists/update', 'id' => $model->id],
            'visible' => $model->userCan(\prime\models\permissions\Permission::PERMISSION_WRITE)
        ]
    ]
];

?>
<div class="col-xs-12">
    <h2><?=$this->title?></h2>
    <?php

    echo \yii\grid\GridView::widget([
        'dataProvider' => new \yii\data\ArrayDataProvider([
            'allModels' => $model->getUsers()->all(),
            'sort' => [
                'attributes' => [
                    'name',
                    'email'
                ]
            ]

        ]),
        'columns' => [
            'name',
            'email'
        ]
    ]);
    ?>
</div>
