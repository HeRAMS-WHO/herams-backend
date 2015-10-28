<?php

/**
 * @var \yii\web\View $this
 * @var \prime\models\UserList $model
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
            ]
        ],
        [
            'label' => \Yii::t('app', 'Edit'),
            'url' => ['/user-lists/update', 'id' => $model->id]
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
