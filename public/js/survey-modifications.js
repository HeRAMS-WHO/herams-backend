'use strict'
window.__herams_init_callbacks = window.__herams_init_callbacks ?? []
window.__herams_init_callbacks.unshift(async () => {
  if (typeof window.Survey === 'undefined') {
    throw new Error('SurveyJS not (yet) loaded')
  }

  console.log('Applying survey modifications');
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

  // New question type for facility type:
  Survey.CustomWidgetCollection.Instance.add({
    name: 'facilitytype',
    title: 'Facility Type',
    iconName: 'icon-radiogroup',
    category: 'HeRAMS',
    isFit (question) {
      return question.getType() === this.name
    },
    isDefaultRender: true,

    widgetIsLoaded () {
      return true
    },

    init () {
      Survey.Serializer.addClass(this.name + 'value', [], null, 'itemvalue')
      Survey.Serializer.addProperty(this.name + 'value', {
        name: 'tier',
        displayName: 'Tier',
        choices: ['primary', 'secondary', 'tertiary'],
        default: 'primary',
      })

      Survey.Serializer.addClass(this.name, [
        {
          name: 'choices',
          type: this.name + 'value[]',

        },
        {
          name: 'readOnly',
          visible: false,
        },
        {
          name: 'choicesFromQuestion',
          visible: false,
        },
        {
          name: 'hasOther',
          visible: false,
        },
        {
          name: 'hasComment',
          visible: false,
        },
        {
          name: 'separateSpecialChoices',
          visible: false,
        },
        {
          name: 'hasNone',
          visible: false,
        },
      ], null, 'radiogroup')
    },
    afterRender (question, el) {

    },

  }, 'customtype')

  console.log('waiting for locales')
  const platformLocales = await window.Herams.fetchWithCsrf('/api/configuration/locales', null, 'GET')
  console.log('done')
  Survey.ComponentCollection.Instance.add({
    name: 'localizableprojecttext',
    title: 'Localizable project text',
    iconName: 'icon-text',
    category: 'HeRAMS',
    questionJSON: {
      type: 'multipletext',
      items: platformLocales.map(({ label, locale }) => ({ name: locale, title: label })),
    },
    onInit () {
      console.log(this)
    },
    onLoaded (question) {
      if (!question?.survey?.locales || question.survey.locales.length === 0) return
      question.contentQuestion.items = question.contentQuestion.items.filter((item) => question.survey.locales.includes(item.name))
    },

  })

  Survey.ComponentCollection.Instance.add({
    iconName: 'icon-language',
    name: 'platformlanguagepicker',
    title: 'Platform language picker',
    category: 'HeRAMS',
    questionJSON: {
      type: 'checkbox',
      name: 'languages',
      title: 'Additional languages for this project, English is always enabled',
      choices: platformLocales.map(({ label, locale }) => ({ value: locale, text: label })),
    },
    onInit () {
      // debugger;
    },
  })

  Survey.ComponentCollection.Instance.add({
    iconName: 'icon-visible',
    name: 'projectvisibility',
    title: 'Project visibility picker',
    category: 'HeRAMS',
    questionJSON: {
      type: 'radiogroup',
      name: 'projectvisibility',
      title: 'Project visibility',
      isRequired: true,
      choices: [
        {
          value: 'hidden',
          text: 'Hidden',
        },
        {
          value: 'public',
          text: 'Public',
        },
        {
          value: 'private',
          text: 'Private',
        },
      ],
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
        url: '/api/configuration/countries',
        valueName: 'alpha3',
        titleName: 'name',
      },
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
        url: '/api/surveys',
        valueName: 'id',
        titleName: 'title',
      },
    },
    onInit () {
      // debugger;
    },
  })
  console.log('Remaining question types', Object.keys(Survey.QuestionFactory.Instance.creatorHash))
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
    type: 'boolean',
    default: false,
  })

  Survey.defaultV2Css.saveData.success = 'success'
  Survey.defaultV2Css.saveData.root = 'savedata'
  Survey.StylesManager.applyTheme('defaultV2')
})
