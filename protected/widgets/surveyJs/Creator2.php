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
    private array $clientOptions = [
        'showState' => true,
        'showTranslationTab' => true,
    ];

    private array $options = [
        'style' => [
            'height' => '800px',
        ],
    ];

    public null|SurveyId $surveyId = null;

    private string $result;

    private string $config;

    public function setSurveyId(null|SurveyId $surveyId)
    {
        $this->surveyId = $surveyId;
    }

    private function registerAssetBundles(): void
    {
        $this->view->registerAssetBundle(SurveyJsCreator2Bundle::class);
    }

    public function getConfig(): string
    {
        return $this->config;
    }

    public function getResult(): string
    {
        return $this->result;
    }

    public function setConfig($surveyId = null)
    {
        if (! is_null($surveyId)) {
            $this->setSurveyId($surveyId);
        }
        $htmlOptions = [
            'id' => $this->getId(),
            ...$this->options,
        ];
        $this->result = Html::tag('div', '', $htmlOptions);
        $this->config = Json::encode([
            'creatorOptions' => $this->clientOptions,
            'createUrl' => Url::to(['/api/survey/create']),
            'dataUrl' => isset($this->surveyId) ? Url::to([
                '/api/survey/view',
                'id' => $this->surveyId,
            ]) : null,
            'elementId' => $htmlOptions['id'],
            'updateUrl' => Url::to(
                [
                    '/survey/update',
                    'id' => 10101010,
                ],
                true
            ),
        ]);
    }

    public function run(): string
    {
        $this->registerAssetBundles();
        $this->setConfig();

        $this->view->registerJs(
            <<<JS
            console.profile('creator');
            const config = {$this->getConfig()}
            
            // This is the function for updating.
            const updateSurvey = async (saveNo, callback) => {
                try {
                    const response = await Herams.fetchWithCsrf(config.dataUrl, {config: surveyCreator.JSON}, 'PUT');
                    console.warn('response', response);
                    callback(saveNo, true);
                } catch (e) {
                    console.error(e);
                    callback(saveNo, false);
                }
            };
            // This is the function for creating a new survey.
            const createSurvey = async (saveNo, callback) => {
                
                try {
                    const surveyUrl = await Herams.createInCollectionWithCsrf(config.createUrl, {config: surveyCreator.JSON})
                    config.dataUrl = surveyUrl;
                    // This is just a minor UX improvement; we set the current page url to the update url.
                    const id = surveyUrl.match(/\d+/)[0]
                    history.replaceState({}, '', config.updateUrl.replace('10101010', id))
                    callback(saveNo, true);
                } catch (e) {
                    console.error(e);
                    callback(saveNo, false);
                }
            }
            
            const element = document.getElementById(config.elementId);
            const surveyCreator = new SurveyCreator.SurveyCreator(config.creatorOptions);
            surveyCreator.toolbox.allowExpandMultipleCategories = true
            surveyCreator.haveCommercialLicense = false
            window.surveyCreator = surveyCreator;
            
            surveyCreator.saveSurveyFunc = (saveNo, callback) => config.dataUrl ? updateSurvey(saveNo, callback) : createSurvey(saveNo, callback);
            
            // Check if we need to load survey JSON
            if (config.dataUrl) {
                const data = await window.Herams.fetchWithCsrf(config.dataUrl, null, 'GET');
                surveyCreator.JSON = data.config;
            }
            surveyCreator.render(element);
            console.profileEnd();

        JS,
            View::POS_HERAMS_INIT
        );

        return $this->result;
    }
}
