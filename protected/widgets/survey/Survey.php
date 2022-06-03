<?php

declare(strict_types=1);

namespace prime\widgets\survey;

use prime\assets\SurveyJsBundle;
use prime\assets\SurveyModificationBundle;
use prime\objects\LanguageSet;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;

class Survey extends Widget
{
    private array $config;

    private array $data;

    private LanguageSet $languages;

    private array $submitRoute;

    public function init(): void
    {
        parent::init();
        SurveyModificationBundle::register($this->view);
    }

    public function withConfig(array $config): self
    {
        $this->config = $config;
        return $this;
    }

    public function withData(array $data): self
    {
        $this->data = $data;
        return $this;
    }

    public function withLanguages(LanguageSet $languageSet): self
    {
        $this->languages = $languageSet;
        return $this;
    }

    public function withSubmitRoute(array $route): self
    {
        $this->submitRoute = $route;
        return $this;
    }

    public function run(): string
    {
        $structure = Json::encode($this->config);
        $data = Json::encode($this->data ?? []);
        $languages = Json::encode($this->languages->toArray());
        $this->view->registerJs(
            <<<JS
Survey
    .StylesManager
    .applyTheme("defaultV2");
console.log($structure);
const survey = new Survey.Model($structure);
survey.data = $data;
window.surveys = window.surveys ?? [];
window.surveys.push(survey);
survey.onServerValidateQuestions.add((sender, options) => {
    // Data is in options.data
    options.errors["promoter_features"] = "Server side error";
    options.complete();
});
;

JS
        );
        if (isset($this->submitRoute)) {
            $url = Json::encode(Url::to($this->submitRoute));
            $this->view->registerJs(
                <<<JS
            survey.onComplete.add(async (sender, options) => {
                options.showDataSaving();
                try {
                    const response = await fetch($url, {
                        method: 'POST',
                        mode: 'cors',
                        redirect: 'error',
                        cache: 'no-cache',
                        headers: {
                            'Accept': 'application/json;indent=2',
                            'Content-Type': 'application/json',
                            'X-CSRF-Token': yii.getCsrfToken(),
                        },
                        body: JSON.stringify({
                            data: sender.data
                        }),
                    });
                    if (response.ok) {
                        // Check if we have a redirect header.
                        options.showDataSavingSuccess(response.statusText);
                        if (response.headers.has('X-Suggested-Location')) {
                            window.location.href = response.headers.get('X-Suggested-Location');    
                        } else if (response.headers.has('Location')) {
                            window.location.href = response.headers.get('Location');
                        }
                        
                    } else {
                        options.showDataSavingError(response.statusText);
                    }
                } catch(error) {
                    options.showDataSavingError(error);
                }                
                
                
            });
            JS
            );
        }
        $id = Json::encode($this->getId());
        $this->view->registerJs("survey.render($id);");
        return Html::tag('div', 'Survey here', [
            'id' => $this->getId(),
        ]);
    }
}
