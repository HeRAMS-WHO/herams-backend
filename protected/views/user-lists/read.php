<?php

/**
 * @var \yii\web\View $this
 * @var \prime\models\UserList $model
 */

$this->title = Yii::t('app', 'User list {name}', ['name' => $model->name]);

$this->params['subMenu'] = [
    'items' => [
        [
            'label' => \Yii::t('app', 'Edit'),
            'url' => ['user-lists/update', 'id' => $model->id]
        ],
    ]
];

?>
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
