<?php

declare(strict_types=1);

use kartik\grid\GridView;
use prime\components\View;
use prime\interfaces\FacilityForTabMenu;
use prime\interfaces\ResponseForList;
use prime\models\ar\Permission;
use prime\widgets\menu\FacilityTabMenu;
use prime\widgets\Section;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\User;

/**
 * @var ActiveDataProvider $responseProvider
 * @var View $this
 * @var FacilityForTabMenu $facility
 */

$this->title = $facility->getTitle();

$this->beginBlock('tabs');
echo FacilityTabMenu::widget(
    ['facility' => $facility]
);
$this->endBlock();

Section::begin()
    ->withActions([
        [
            'label' => \Yii::t('app', 'Update admin for facility'),
            'link' => Url::to(['update', 'id' => $facility->getId()]),
            'permission' => function (FacilityForTabMenu $facility, User $userComponent) {
                return $facility->canReceiveSituationUpdate() && $facility->canCurrentUser(Permission::PERMISSION_WRITE);
            }
        ],
    ])
    ->withSubject($facility)
    ->withHeader(\Yii::t('app', 'Admin responses'));

echo GridView::widget([
    'dataProvider' => $responseProvider,
    'columns' => [
        ResponseForList::ID,
        'dateOfUpdate',
    ]
]);

Section::end();
