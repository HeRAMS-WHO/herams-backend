
((Survey) => {
    [
        "mode",
        "navigateToUrl",
        "navigateToUrlOnCondition",
        "maxTimeToFinish",
        "maxTimeToFinishPage",
        "showTimerPanel",
        "showTimerPanelMode",
        "completedBeforeHtml",
        "completedHtml",
        "completedHtmlOnCondition",
        "showCompletedPage",
        "loadingHtml",
        "storeOthersAsComment",
        "sendResultOnPageNext",
        "requiredText",
        "questionStartIndex",
        "logo",
        "logoFit",
        "logoHeight",
        "logoPosition",
        "logoWidth",
        "locale",
        "title",
        "showTitle",
        "description",
        "cookieName",
        "showPreviewBeforeComplete",
        "pageNextText",
        "pagePrevText",
        "startSurveyText",
        "requiredText",
        "completeText",
        "previewText",
        "emptySurveyText",
        "editText",
        "firstPageIsStarted",
        "progressText"


    ].forEach((property) => Survey.Serializer.removeProperty("survey", property));

    Survey.Serializer.removeProperty("selectBase", "choicesByUrl");
    [
        "signaturepad",
        "file",
        "multipletext",
        "paneldynamic",
        "matrixdynamic",
        "comment",
        "imagepicker",
        "rating",
        "matrix",
        "image",
        "expression"

    ].forEach(Survey.QuestionFactory.Instance.unregisterElement, Survey.QuestionFactory.Instance);
    /**
     * Todo:
     * sendResultOnPageNext --> should be forced to true
     */

// New question type for facility type:
    const facilityQuestionType = {
        name: "facilityType",
        title: "Facility Type",
        iconName: "icon-radiogroup",
        category: "HeRAMS",
        isFit(question) {
            return question.getType() === 'facilityType';
        },
        isDefaultRender: true,

        widgetIsLoaded() {
            return true
        },

        init() {
            Survey.Serializer.addClass("facilityitemvalue", [
                {
                    name: "tier",
                    choices: ["primary", "secondary", "tertiary"]
                }, {
                    name: "text"
                }
            ], null, "itemvalue");

            Survey.Serializer.addClass("facilityType", [
                {
                    name: "choices",
                    type: "facilityitemvalue[]"
                },
            ], null, "dropdown");

        }
    };

    Survey.CustomWidgetCollection.Instance.add(facilityQuestionType, "customtype");

    // Add expression properties to survey
    Survey.Serializer.addProperty('survey', {
        name: "canReceiveSituationUpdate",
        displayName: "Can receive a situation update (expression)",
        isRequired: true,
        type: "string",
    });

    Survey.Serializer.addProperty('survey', {
        name: "platformUseInList",
        displayName: "Use for list (expression)",
        isRequired: true,
        type: "string",
    });

    Survey.Serializer.addProperty('survey', {
        name: "platformUseInDashboarding",
        displayName: "Use in dashboarding (expression)",
        isRequired: true,
        type: "string",
    });

    Survey.Serializer.addProperty('survey', {
        name: "platformUseForSituationUpdate",
        displayName: "Use in situation update (expression)",
        isRequired: true,
        type: "string",
    });
})(Survey);
