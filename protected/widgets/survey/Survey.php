<?php

declare(strict_types=1);

namespace prime\widgets\survey;

use herams\common\values\ProjectId;
use prime\assets\SurveyModificationBundle;
use prime\components\View;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;

class Survey extends Widget
{
    private array $config;

    /**
     * @var array Data dat should be submitted with the result
     */
    private array $extraData;

    private array $data;

    private array|string $submitRoute;

    private array|string $serverValidationRoute;

    private array|string $redirectRoute;

    private array|string $dataRoute;

    private array $dataPath = [];

    private null|string $localeEndpoint = null;

    private bool $displayMode = false;

    public function init(): void
    {
        parent::init();
        SurveyModificationBundle::register($this->view);
    }

    public function inDisplayMode(): self
    {
        $this->displayMode = true;
        return $this;
    }

    public function withExtraData(array $extraData): self
    {
        $this->extraData = $extraData;
        return $this;
    }

    public function withProjectId(ProjectId $projectId): self
    {
        $this->localeEndpoint = Url::to([
            '/api/project/view',
            'id' => $projectId,
        ]);
        return $this;
    }

    public function withConfig(array $config): self
    {
        $this->config = $config;
        return $this;
    }

    public function withDataRoute(array|string $route, array $dataPath = []): self
    {
        $this->dataRoute = $route;
        $this->dataPath = $dataPath;
        return $this;
    }

    public function withSubmitRoute(array|string $route): self
    {
        $this->submitRoute = $route;
        return $this;
    }

    public function withServerValidationRoute(array|string $route): self
    {
        $this->serverValidationRoute = $route;
        return $this;
    }

    public function withRedirectRoute(array|string $route): self
    {
        $this->redirectRoute = $route;
        return $this;
    }

    public function run(): string
    {
        $config = Json::encode([
            'structure' => $this->config,
            'data' => $this->data ?? [],
            'extraData' => $this->extraData ?? null,
            'submissionUrl' => isset($this->submitRoute) ? Url::to($this->submitRoute) : null,
            'validationUrl' => isset($this->serverValidationRoute) ? Url::to($this->serverValidationRoute) : null,
            'dataUrl' => isset($this->dataRoute) ? Url::to($this->dataRoute) : null,
            'dataPath' => $this->dataPath,
            'redirectUrl' => isset($this->redirectRoute) ? Url::to($this->redirectRoute) : null,
            'elementId' => $this->getId(),
            'displayMode' => $this->displayMode,
            'localeEndpoint' => $this->localeEndpoint,
        ]);
        $this->view->registerJs(
            <<<JS
        // await new Promise((resolve) => setTimeout(resolve, 5000));
            const config = {$config};
            
            const surveyStructure = config.structure
            
            // if (config.localeEndpoint) {
            //     const locales = await Herams.fetchWithCsrf(config.localeEndpoint, null, 'get')
            //     surveyStructure.locales = locales.languages 
            // }
            //
            //
            const survey = new SurveyKnockout.Survey(surveyStructure);
            
            survey.mode = config.displayMode ? "display" : "edit";
            
            let restartWithFreshData
            let waitForDataPromise
            if (config.dataUrl) {
                restartWithFreshData = async () => {
                    console.log("Clearing survey");
                    survey.clear()
                    let data = await window.Herams.fetchWithCsrf(config.dataUrl, null, 'GET');
                    for (const pathElement of config.dataPath) {
                        data = data[pathElement]
                    }
                    try {
                    survey.data = { ...data, ...config.data }
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
                    return survey.data;
                }
                waitForDataPromise = restartWithFreshData();
               
            } else {
                waitForDataPromise = new Promise((resolve, reject) => {
                    survey.data = config.data
                    resolve(config.data)
                })
               
            }


            window.surveys = window.surveys ?? [];
            window.surveys.push(survey);
            survey.surveyShowDataSaving = true;
            if (config.submissionUrl) {
                survey.onComplete.add(async (sender, options) => {
                    options.showDataSaving('Uploading data');
                    try {
                        const json = await window.Herams.fetchWithCsrf(config.submissionUrl, {
                            ...(config.extraData ?? {}),
                            data: sender.data
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
                            ...(config.extraData ?? {}),
                            data: sender.data
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

            
            const data = await waitForDataPromise
            
            console.log('rendering survey',  survey, data)
            survey.render(config.elementId);
            window.survey = survey;
        JS,
            View::POS_HERAMS_INIT
        );

        return Html::tag('div',
            'Loading...' . Html::tag('pre', json_encode($this->config, JSON_PRETTY_PRINT)),
        options: [
            'id' => $this->getId(),
        ]);
    }
}
