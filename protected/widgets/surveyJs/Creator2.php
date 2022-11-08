<?php

declare(strict_types=1);

namespace prime\widgets\surveyJs;

use herams\common\values\SurveyId;
use prime\assets\SurveyJsCreator2Bundle;
use prime\components\View;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;

final class Creator2 extends Widget
{
    public array $clientOptions = [];

    public array $options = [
        'style' => [
            'height' => '800px',
        ],
    ];

    /**
     * JavaScript methods that are called after the initialization and get the surveyCreate as an argument
     *
     */
    public array $surveyCreatorCustomizers = [];

    public null|SurveyId $surveyId = null;

    private function registerAssetBundles(): void
    {
        $this->view->registerAssetBundle(SurveyJsCreator2Bundle::class);
    }

    public function run(): string
    {
        $this->registerAssetBundles();
        $htmlOptions = ['id' => $this->getId(), ...$this->options];
        $result = Html::tag('div', '', $htmlOptions);

        $config = Json::encode([
            'creatorOptions' => $this->clientOptions,
            'createEndpoint' => Url::to(['/api/survey/create']),
            'updateEndpoint' => Url::to(['/api/survey/update', 'id' => '__id__']),
            'idPlaceholder' => '__id__',
            'customizers' => array_values($this->surveyCreatorCustomizers),
            'elementId' => $htmlOptions['id'],
            'surveyId' => $this->surveyId

        ]);
        $this->view->registerJs(
            <<<JS
            const config = {$config}
            if (!config.updateEndpoint.includes(config.idPlaceholder)) {
                throw `ID placeholder \${config.idPlaceholder} not found in \${config.updateEndpoint}`;
            }
            const element = document.getElementById(config.elementId);
            console.log('creating creator', config);
            const surveyCreator = new SurveyCreator.SurveyCreator(config.creatorOptions);
            surveyCreator.haveCommercialLicense = false
            window.surveyCreator = surveyCreator;
            config.customizers.forEach(customizer => customizer(surveyCreator));
            surveyCreator.render(element);


        JS,
            View::POS_HERAMS_INIT
        );

        return $result;
    }
}
