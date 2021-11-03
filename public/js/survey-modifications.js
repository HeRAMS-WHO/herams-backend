/**
Survey.Serializer.addProperty("text", {
    name: "isPostalCode",
    type: "switch",
    category: "Statistics & analytics",
});
*/
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
Survey.QuestionFactory.Instance.unregisterElement("signaturepad");
Survey.QuestionFactory.Instance.unregisterElement("file");
Survey.QuestionFactory.Instance.unregisterElement("multipletext");
Survey.QuestionFactory.Instance.unregisterElement("paneldynamic");
Survey.QuestionFactory.Instance.unregisterElement("matrixdynamic");
Survey.QuestionFactory.Instance.unregisterElement("comment");
Survey.QuestionFactory.Instance.unregisterElement("expression");
/**
 * Todo:
 * sendResultOnPageNext --> should be forced to true
 */
