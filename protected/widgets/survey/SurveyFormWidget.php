<?php

declare(strict_types=1);

namespace prime\widgets\survey;

use prime\assets\SurveyModificationBundle;
use prime\components\View;
use prime\interfaces\SurveyFormInterface;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;

class SurveyFormWidget extends Widget
{
    private SurveyFormInterface $surveyForm;

    private string $config;

    public function init(): void
    {
        parent::init();
        SurveyModificationBundle::register($this->view);
    }

    public function withForm(SurveyFormInterface $surveyForm): self
    {
        $this->surveyForm = $surveyForm;
        return $this;
    }

    public function setConfig()
    {
        $this->config = Json::encode([
            'structure' => $this->surveyForm->getConfiguration(),
            'extraData' => $this->surveyForm->getExtraData(),
            'submissionUrl' => Url::to($this->surveyForm->getSubmitRoute()),
            'validationUrl' => Url::to($this->surveyForm->getServerValidationRoute()),
            'dataUrl' => Url::to($this->surveyForm->getDataRoute()),
            'redirectUrl' => Url::to($this->surveyForm->getRedirectRoute()),
            'elementId' => $this->getId(),
            'localeEndpoint' => $this->surveyForm->getLocaleEndpoint(),
        ]);
    }

    public function getConfig(): string
    {
        return $this->config;
    }

    public function run(): string
    {
        $this->setConfig();
        $this->view->registerJs(
            <<<JS
            
            const config = {$this->config};
            window.surveyContainer = config.elementId;
            const surveyStructure = config.structure
            
            if (config.localeEndpoint) {
                const locales = await Herams.fetchWithCsrf(config.localeEndpoint, null, 'get')
                surveyStructure.locales = locales.languages 
            }
            const survey = new Survey.Model(surveyStructure);
            
            let restartWithFreshData
            let waitForDataPromise
            if (config.dataUrl) {
                restartWithFreshData = async () => {
                    console.log("Clearing survey");
                    //survey.clear()
                    const data = await window.Herams.fetchWithCsrf(config.dataUrl, null, 'GET');
                    console.log(survey.data)
                    try {
                        data.projectvisibility = data.visibility
                        
                        //survey.data = data
                    } catch (error) {
                        survey.data = {};
                        console.warn("Fallback to setting individual values", error);
                        for(const [key, value] of Object.entries({ ...data, ...config.data })) {
                            try {
                                console.log("Setting", key, value);
                                survey.setValue(key, value);
                            } catch (error) {
                                console.warn("Failed to set", key, value, error);
                            }
                        }
                    }
                    //return survey.data;
                    console.log(survey.data)
                }
                waitForDataPromise = restartWithFreshData();
               
            }


            window.surveys = window.surveys ?? [];
            window.surveys.push(survey);
            survey.surveyShowDataSaving = true;
            if (config.submissionUrl) {
                survey.onComplete.add(async (sender, options) => {
                    options.showDataSaving('Uploading data');
                    try {
                        await window.Herams.fetchWithCsrf(config.submissionUrl, {
                            data: {
                                ...(config.extraData),
                                ...sender.data
                            }
                        })
                        options.showDataSavingSuccess('Data saved');
                        const notification = window.Herams.notifySuccess("Data saved", 'center');
                        if (config.redirectUrl) {
                            await notification
                            window.location.assign(config.redirectUrl);
                        } else if (restartWithFreshData) {
                            return restartWithFreshData()
                        }
                       
                    } catch(error) {
                        if (Object.getPrototypeOf(error).name === 'ValidationError') {
                            options.showDataSavingError(error.message + ': ' + JSON.stringify(error.errors));    
                        } else {
                            options.showDataSavingError(error.message);
                        }
                       
                       
                    }                
                });
            }

            if (config.validationUrl) {
                survey.onServerValidateQuestions.add(async (sender, options) => {
                   
                    try {
                        const json = await window.Herams.fetchWithCsrf(config.validationUrl, {
                            data: {
                                ...(config.extraData),
                                ...sender.data
                            }
                            
                        });
                        let visibleError = false
                        console.log(json, options.data);
                        for (const [attribute, errors] of Object.entries(json.errors)) {
                            options.errors[attribute] = errors.join(', ');
                            visibleError = visibleError 
                                || typeof options.data[attribute] !== 'undefined'
                                || surveys[0].currentPage.getQuestionByName(attribute)?.isVisible
                        }
                       
                        // If the error is not visible, add it to all questions
                        if (!visibleError) {
                            for (const question of sender.currentPage.questions) {
                                for (const [attribute, errors] of Object.entries(json.errors)) {
                                    options.errors[question.name] = errors.join(', ');
                                }
                            }
                        }
                       
                    } catch (error) {
                        // This is a big error, add it to all questions on the page.
                        for (const question of sender.currentPage.questions) {
                            options.errors[question.name] = error.message
                        }
                    }
                    options.complete();
                });
            }

            
            // const data = await waitForDataPromise
            //
            // console.log('rendering survey',  survey, data)
            ko.applyBindings({
                model: survey
            })
            window.survey = survey;
        JS,
            View::POS_HERAMS_INIT
        );

        return Html::tag(
            'survey',
            'Loading...' . Html::tag('pre', json_encode($this->surveyForm->getConfiguration(), JSON_PRETTY_PRINT)),
            options: [
                'id' => $this->getId(),
                'params' => "survey: model",
            ]
        );
    }
}
