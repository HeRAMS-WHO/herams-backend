<?php

declare(strict_types=1);

namespace prime\widgets\survey;

use prime\assets\SurveyJsBundle;
use prime\objects\LanguageSet;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;

class Survey extends Widget
{
    public array $submitRoute;
    public array $data;

    public LanguageSet $languages;

    public function init(): void
    {
        parent::init();
        SurveyJsBundle::register($this->view);
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


        $structure = [
            "completedHtml" => "good",
            "pages" => [
                [
                    "name" => "page1",
                    "elements" => [
                        [
                            "type" => "text",
                            "name" => "name",
                            "title" => \Yii::t('app', "Facility name"),
                            "isRequired" => true,
                        ],
                        [
                            "type" => "text",
                            "name" => "i18nName[ar]",
                            "title" => \Yii::t('app', "Facility name (Arabic)"),
                        ],
                        [
                            "type" => "text",
                            "name" => "i18nName[fr]",
                            "title" => \Yii::t('app', "Facility name (French)"),
                        ],
                        [
                            "type" => "text",
                            "name" => "alternative_name",
                            "title" => \Yii::t('app', "Alternative name"),
                            "isRequired" => false,

                        ],

                        [
                            "type" => "text",
                            "name" => "coordinates",
                            "title" => \Yii::t('app', "Coordinates"),
                            "isRequired" => false,

                        ],
                    ]
                ]
            ],
            "showQuestionNumbers" => "off"
        ];



        $structure = Json::encode($structure);
        $data = Json::encode($this->data ?? []);
        $languages = Json::encode($this->languages->toArray());
        $this->view->registerJs(<<<JS
Survey
    .StylesManager
    .applyTheme("modern");
const survey = new Survey.Model($structure);
survey.data = $data;
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
            $this->view->registerJs(<<<JS
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
        return Html::tag('div', '', ['id' => $this->getId()]);
    }
}
