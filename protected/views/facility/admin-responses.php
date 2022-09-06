<?php

declare(strict_types=1);

use prime\components\View;
use prime\interfaces\AdminResponseForListInterface;
use prime\interfaces\FacilityForTabMenu;
use prime\models\ar\Permission;
use prime\widgets\DataColumn;
use prime\widgets\GridView;
use prime\widgets\menu\FacilityTabMenu;
use prime\widgets\Section;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\User;

/**
 * @var ActiveDataProvider $responseProvider
 * @var View $this
 * @var FacilityForTabMenu $facility
 * @var iterable<\Collecthor\DataInterfaces\VariableInterface> $variables
 */

$this->title = $facility->getTitle();

$this->beginBlock('tabs');
echo FacilityTabMenu::widget(
    [
        'facility' => $facility,
    ]
);
$this->endBlock();

Section::begin()
    ->withActions([
        [
            'label' => \Yii::t('app', 'Update admin for facility'),
            'link' => Url::to([
                'update',
                'id' => $facility->getId(),
            ]),
            'permission' => function (FacilityForTabMenu $facility, User $userComponent) {
                return $facility->canReceiveSituationUpdate() && $facility->canCurrentUser(Permission::PERMISSION_WRITE);
            },
        ],
    ])
    ->withSubject($facility)
    ->withHeader(\Yii::t('app', 'Admin responses'));

echo GridView::widget([
    'dataProvider' => $responseProvider,
    'columns' => [
        AdminResponseForListInterface::ID,
        [
            'class' => DataColumn::class,
            'attribute' => AdminResponseForListInterface::DATE_OF_UPDATE,
            'format' => 'raw',
            'value' => function (AdminResponseForListInterface $response) {
                $label = \Yii::$app->formatter->asDate($response->getDateOfUpdate());
                $icon = \prime\helpers\Icon::eye();
                return Html::a($label . $icon, [
                    '/response/view',
                    'id' => $response->getId(),
                ]);
            },

        ],
        \prime\widgets\VariableColumn::configForVariables(...$variables),
        [
            'class' => DataColumn::class,
            'attribute' => 'data',
            'format' => 'json',
        ],
    ],
]);

Section::end();
