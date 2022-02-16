
((Survey) => {
    [
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
        "requiredText",
        "questionStartIndex",
        // "logo",
        // "logoFit",
        // "logoHeight",
        // "logoPosition",
        // "logoWidth",
        // "locale",
        // "title",
        // "showTitle",
        // "description",
        "cookieName",
        "showPreviewBeforeComplete",
        // "pageNextText",
        // "pagePrevText",
        // "startSurveyText",
        // "requiredText",
        // "completeText",
        // "previewText",
        "emptySurveyText",
        // "editText",
        // "firstPageIsStarted",
        // "progressText"


    ].forEach((property) => Survey.Serializer.removeProperty("survey", property));
    [
        "storeOthersAsComment",
        "mode",
        "sendResultOnPageNext",


    ].forEach((property) => Survey.Serializer.findProperty("survey", property).visible = false);

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
    console.log("Removed question types", Object.keys(Survey.QuestionFactory.Instance.creatorHash));
    /**
     * Todo:
     * sendResultOnPageNext --> should be forced to true
     */
    Survey.Serializer.addClass("coloritemvalue", [
        {
            name: "text",
            visible: false,
        },
        {
            name: "color",
            type: "color"
        },
    ],
    null,
    "itemvalue");
    Survey.Serializer.addProperty("survey", {
        category: "Reporting & Dashboarding",
        default: [
            {
                value: "A1",
                color: "#ff0000"
            },
            {
                value: "A2",
                color: "#00ff00"
            }
        ],
        isLocalizable: false,
        className: "coloritemvalue",
        type: "coloritemvalue[]",
        name: "colors",
        displayName: "Color Dictionary",


    });

// New question type for facility type:
    const widgetName = "facilitytype"
    const facilityQuestionType = {
        name: widgetName,
        title: "Facility Type",
        iconName: "icon-radiogroup",
        category: "HeRAMS",
        isFit(question) {
            return question.getType() === widgetName;
        },
        isDefaultRender: true,

        widgetIsLoaded() {
            return true
        },

        init() {
            Survey.Serializer.addClass("facilityitemvalue", [
                {
                    name: "tier",
                    displayName: "Tier",
                    choices: ["primary", "secondary", "tertiary"]
                }, {
                    name: "text"
                }
            ], null, "itemvalue");

            Survey.Serializer.addClass(widgetName, [
                {
                    name: "choices",
                    type: "facilityitemvalue[]"

                },
            ], null, "radiogroup");

        },
        afterRender(question, el) {

        },

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
