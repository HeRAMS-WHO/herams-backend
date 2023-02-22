'use strict';
((Survey) => {
  Survey.ComponentCollection.Instance.add({
    name: 'localizableprojecttext',
    title: 'Localizable project text',
    iconName: 'icon-text',
    category: 'HeRAMS',
    questionJSON: {
      type: 'multipletext',
    },
    onInit () {
      console.log(this)
    },
    async onLoaded (question) {
      const response = await fetch('/api-proxy/core/configuration/locales')
      const platformLocales = await response.json()
      const surveyLocales = question.survey?.locales ?? []
      for (const { locale, label } of platformLocales) {
        if (surveyLocales.includes(locale) || surveyLocales.length === 0) {
          const item = question.contentQuestion.addItem(locale, label)
          item.isRequired = surveyLocales.length === 0 && locale === 'en'
        }
      }
    },

  })
})(Survey)
