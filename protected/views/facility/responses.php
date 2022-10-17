<?php

declare(strict_types=1);

use prime\components\View;
use prime\helpers\Icon;
use prime\interfaces\FacilityForTabMenu;
use prime\models\ar\Permission;
use prime\widgets\menu\FacilityTabMenu;
use prime\widgets\Section;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\User;

/**
 * @var FacilityForTabMenu $facility
 * @var \prime\values\FacilityId $facilityId
 * @var ActiveDataProvider $responseProvider
 * @var View $this
 * @var array $updateSituationUrl
 * @var iterable<\Collecthor\DataInterfaces\VariableInterface> $variables
 */

$this->params['subject'] = Icon::healthFacility() . $facility->getTitle();
$this->title = \Yii::t('app', 'Responses');

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
            'label' => \Yii::t('app', 'Update situation for facility'),
            'link' => Url::to($updateSituationUrl),
            'permission' => function (FacilityForTabMenu $facility, User $userComponent) {
                return $facility->canReceiveSituationUpdate() && $facility->canCurrentUser(Permission::PERMISSION_SURVEY_DATA);
            },
        ],
    ])
    ->withSubject($facility)
    ->withHeader(\Yii::t('app', 'Responses'));
echo \prime\widgets\AgGrid\AgGrid::widget([
    'route' => [
        '/api/facility/data-responses',
        'id' => $facilityId,
    ],
    'columns' => [
        [

            'headerName' => \Yii::t('app', 'Name'),
            'field' => 'name',
            //            'cellRenderer' => new \yii\web\JsExpression(<<<JS
            //                params => {
            //                    const a = document.createElement('a');
            //                    a.textContent = params.value;
            //                    a.href = '/facility/{id}/responses'.replace('{id}', params.data.id);
            //                    return a;
            //                }
            //            JS),
            //            'filter' => 'agNumberColumnFilter',
        ],
        [

            'headerName' => \Yii::t('app', 'Id'),
            'field' => 'id',
            'filter' => 'agNumberColumnFilter',
        ],
        ...\iter\map(fn (VariableInterface $variable) => [
            'field' => $variable->getName(),
            'headerName' => $variable->getTitle(\Yii::$app->language),
        ], $variables),
    ],

]);

Section::end();
