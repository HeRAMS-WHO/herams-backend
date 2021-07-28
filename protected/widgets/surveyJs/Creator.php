<?php
declare(strict_types=1);

namespace prime\widgets\surveyJs;

use prime\assets\SurveyJsCreatorBundle;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\JsExpression;

class Creator extends Widget
{
    public array $clientOptions = [];
    public array $options = [];
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
  surveyCreator.text = '{$survey}';
}
JS
        );
    }

    private function registerAssetBundles(): void
    {
        $this->view->registerAssetBundle(SurveyJsCreatorBundle::class);
    }

    public function run(): string
    {
        $this->registerAssetBundles();

        $result = parent::run();
        $result .= Html::tag('div', '', $this->options);

        $clientOptions = Json::encode($this->clientOptions);
        $surveyJsCustomizers = Json::encode(array_values($this->surveyCreatorCustomizers));
        $this->view->registerJs(<<<JS
const options = {$clientOptions};
const surveyCreator = new SurveyCreator.SurveyCreator("{$this->options['id']}", options);
{$surveyJsCustomizers}.forEach(customizer => customizer(surveyCreator));
JS
        );

        return $result;
    }
}
