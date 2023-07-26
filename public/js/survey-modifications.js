'use strict';

((Survey) => {
  [
    'navigateToUrl',
    'navigateToUrlOnCondition',
    'maxTimeToFinish',
    'maxTimeToFinishPage',
    'showTimerPanel',
    'showTimerPanelMode',
    'completedBeforeHtml',
    'completedHtml',
    'completedHtmlOnCondition',
    'loadingHtml',
    'requiredText',
    'questionStartIndex',
    'logo',
    'logoFit',
    'logoHeight',
    'logoPosition',
    'logoWidth',
    'locale',
    // 'title',
    'showTitle',
    'description',
    'cookieName',
    'showPreviewBeforeComplete',
    // "pageNextText",
    // "pagePrevText",
    // "startSurveyText",
    // "requiredText",
    // "completeText",
    // "previewText",
    'emptySurveyText',
    // "editText",
    // "firstPageIsStarted",
    // "progressText"

  ].forEach((property) => Survey.Serializer.removeProperty('survey', property));
  [
    'storeOthersAsComment',
    'mode',
    'sendResultOnPageNext',
    'showCompletedPage',
  ].forEach((property) => Survey.Serializer.findProperty('survey', property).visible = false)

  /**
   * Todo:
   * sendResultOnPageNext --> should be forced to true
   */
  Survey.Serializer.addClass('coloritemvalue', [
    {
      name: 'text',
      visible: false,
    },
    {
      name: 'color',
      type: 'color',
    },
  ],
  null,
  'itemvalue')
  Survey.Serializer.addProperty('survey', {
    category: 'Reporting & Dashboarding',
    default: [
      {
        value: 'A1',
        color: '#ff0000',
      },
      {
        value: 'A2',
        color: '#00ff00',
      },
    ],
    isLocalizable: false,
    className: 'coloritemvalue',
    type: 'coloritemvalue[]',
    name: 'colors',
    displayName: 'Color Dictionary',

  })

  Survey.ComponentCollection.Instance.add({
    iconName: 'icon-language',
    name: 'platformlanguagepicker',
    title: 'Platform language picker',
    elementsJSON: [
      {
        name: 'languages',
        type: 'checkbox',
        title: 'Languages for this project',
        choicesByUrl: {
          url: `/api-proxy/core/configuration/locales?_lang=${document.documentElement.lang}`,
          valueName: 'locale',
          titleName: 'label',
        },
        defaultValue: ['en'],
      },
      {
        name: 'primaryLanguage',
        type: 'dropdown',
        title: 'Primary language for this project',
        choicesFromQuestion: 'languages',
        choicesFromQuestionMode: 'selected',
        defaultValue: 'en',
      },
    ],
    onInit () {
      console.log(this)
    },

  })

  Survey.ComponentCollection.Instance.add({
    name: 'currentlanguage',
    title: 'Current Language',
    category: 'HeRAMS',
    visible: false,
    questionJSON: {
      name: 'currentLanguage',
      type: 'text',
      defaultValue: `${document.documentElement.lang}`,
    },
    onInit () {
      // debugger;
    },
  })

  Survey.ComponentCollection.Instance.add({
    iconName: 'icon-dropdown',
    name: 'countrypicker',
    title: 'Country picker',
    category: 'HeRAMS',
    questionJSON: {
      type: 'dropdown',
      name: 'country',
      title: 'Pick a country',
      allowClear: false,
      required: true,
      choicesByUrl: {
        url: `/api-proxy/core/configuration/countries?_lang=${document.documentElement.lang}`,
        valueName: 'alpha3',
        titleName: 'name',
      },
    },
    onInit () {
      // debugger;
    },
  })

  Survey.ComponentCollection.Instance.add({
    iconName: 'icon-arrow-right',
    name: 'longitude',
    title: 'Longitude',
    category: 'HeRAMS',
    questionJSON: {
      type: 'text',
      name: 'longitude',
      title: 'Longitude',
      inputType: 'number',
      min: -180,
      max: 180,
      step: 0.00001,
    },
    onInit () {
      // debugger;
    },
  })

  Survey.ComponentCollection.Instance.add({
    iconName: 'icon-keyboard-dragging',
    name: 'latitude',
    title: 'Latitude',
    category: 'HeRAMS',
    questionJSON: {
      type: 'text',
      name: 'longitude',
      title: 'Longitude',
      inputType: 'number',
      min: -90,
      max: 90,
      step: 0.00001,
    },
    onInit () {
      // debugger;
    },
  })

  Survey.ComponentCollection.Instance.add({
    iconName: 'icon-dropdown',
    name: 'surveypicker',
    title: 'Survey picker',
    category: 'HeRAMS',
    questionJSON: {
      type: 'dropdown',
      name: 'survey',
      title: 'Pick a survey',
      allowClear: false,
      required: true,
      choicesByUrl: {
        url: `/api-proxy/core/surveys?_lang=${document.documentElement.lang}`,
        valueName: 'id',
        titleName: 'title',
      },
    },
    onInit () {
      // debugger;
    },
  })
  // Add expression properties to survey
  Survey.Serializer.addProperty('survey', {
    category: 'Reporting & Dashboarding',
    name: 'canReceiveSituationUpdate',
    displayName: 'Can receive a situation update (expression)',
    isRequired: false,
    type: 'string',
    default: '0',
  })

  Survey.Serializer.addProperty('survey', {
    category: 'Reporting & Dashboarding',
    name: 'locales',
    visible: false,
    isRequired: false,
    type: 'string[]',
  })

  Survey.Serializer.addProperty('survey', {
    category: 'Reporting & Dashboarding',
    name: 'platformUseInList',
    displayName: 'Use for list (expression)',
    isRequired: false,
    type: 'string',
    default: '0',
  })

  Survey.Serializer.addProperty('survey', {
    category: 'Reporting & Dashboarding',
    name: 'platformUseInDashboarding',
    displayName: 'Use in dashboarding (expression)',
    isRequired: false,
    type: 'string',
    default: '0',
  })

  Survey.Serializer.addProperty('survey', {
    category: 'Reporting & Dashboarding',
    name: 'platformUseForSituationUpdate',
    displayName: 'Use in situation update (expression)',
    isRequired: false,
    type: 'string',
  })

  Survey.Serializer.addProperty('question', {
    category: 'Reporting & Dashboarding',
    name: 'showInResponseList',
    displayName: 'Show this question in the response list',
    isRequired: false,
    type: 'number',
    // default: false,
  })

  Survey.Serializer.addProperty('question', {
    category: 'Reporting & Dashboarding',
    name: 'showInFacilityList',
    displayName: 'Show this question in the facility list',
    isRequired: false,
    type: 'number',
    // default: false,
  })

  Survey.defaultV2Css.saveData.success = 'success'
  Survey.defaultV2Css.saveData.root = 'savedata'
  Survey.StylesManager.applyTheme('defaultV2')
})(Survey)

window.__herams_init_callbacks = window.__herams_init_callbacks ?? []
window.__herams_init_callbacks.unshift(async () => {
  if (typeof window.Survey === 'undefined') {
    throw new Error('SurveyJS not (yet) loaded')
  }

  // Survey.Serializer.removeProperty('selectBase', 'choicesByUrl')
  // [
  //     "signaturepad",
  //     "file",
  //     "multipletext",
  //     "paneldynamic",
  //     "matrixdynamic",
  //     "comment",
  //     "imagepicker",
  //     "rating",
  //     "matrix",
  //     "image",
  //     "expression"
  //
  // ].forEach(Survey.QuestionFactory.Instance.unregisterElement, Survey.QuestionFactory.Instance);

  // Survey.Serializer.addProperty('survey', {
  //   category: 'general',
  //   choices: ['projectUpdate', 'workspaceUpdate', 'facilityAdmin','facilityData'],
  //   isLocalizable: false,
  //   name: 'surveyType',
  //   displayName: 'Survey type',
  //
  // })

  // New question type for facility type:
})
