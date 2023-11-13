import {ComponentCollection} from "survey-core";

export function applyLocalizableProjectTextQuestion() {
    ComponentCollection.Instance.add({
        name: 'localizableprojecttext',
        title: 'Localizable project text',
        iconName: 'icon-text',
        category: 'HeRAMS',
        questionJSON: {
            type: 'multipletext',
        },
        onInit() {
        },
        async onLoaded(question) {
            const response = await fetch(`/api-proxy/core/configuration/locales?_lang=${document.documentElement.lang}`);
            const platformLocales = await response.json();
            const surveyLocales = question.survey?.locales ?? [];
            for (const {locale, label} of platformLocales) {
                if (surveyLocales.includes(locale) || surveyLocales.length === 0) {
                    const item = question.contentQuestion.addItem(locale, label);
                    item.isRequired = surveyLocales.length === 0 && locale === 'en';
                }
            }
        },

    }, 'customtype')
}
