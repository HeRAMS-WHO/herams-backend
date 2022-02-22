<?php

declare(strict_types=1);

namespace prime\widgets\surveyJs;

use prime\assets\SurveyJsCreator2Bundle;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\JsExpression;

class Creator2 extends Widget
{
    public array $clientOptions = [];
    public array $options = [
        'style' => [
            'height' => '800px'
        ]
    ];
    /** Survey content */
    public array $survey = [];
    /** JavaScript methods that are called after the initialization and get the surveyCreate as an argument */
    public array $surveyCreatorCustomizers = [];

    public function init(): void
    {
        parent::init();

        $this->options['id'] = $this->options['id'] ?? $this->getId();
        $survey = Json::encode($this->survey);
        $this->surveyCreatorCustomizers[] = new JsExpression(<<<JS
function(surveyCreator) {
  surveyCreator.haveCommercialLicense = false;
  surveyCreator.JSON = {$survey};
}
JS
        );
    }

    private function registerAssetBundles(): void
    {
        $this->view->registerAssetBundle(SurveyJsCreator2Bundle::class);
    }

    public function run(): string
    {
        $this->registerAssetBundles();

        $result = parent::run();
        $result .= Html::tag('div', '', $this->options);

        $clientOptions = Json::encode($this->clientOptions);
        $surveyJsCustomizers = Json::encode(array_values($this->surveyCreatorCustomizers));
        $id = Json::encode($this->options['id']);
        $this->view->registerJs(<<<JS
            const element = document.getElementById({$id});
            const options = {$clientOptions};
            console.log(options);
            const surveyCreator = new SurveyCreator.SurveyCreator(options);
            window.surveyCreator = surveyCreator;
            {$surveyJsCustomizers}.forEach(customizer => customizer(surveyCreator));
            surveyCreator.render(element);


JS
        );

        return $result;
    }
}
