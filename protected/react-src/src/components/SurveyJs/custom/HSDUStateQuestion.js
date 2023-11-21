import {CustomWidgetCollection, Serializer} from "survey-core";

export function applyHSDUStateQuestion() {
    CustomWidgetCollection.Instance.add({
        name: 'hsdu_state',
        title: 'HSDU State',
        iconName: 'icon-radiogroup',
        category: 'HeRAMS',
        isFit(question) {
            return question.getType() === this.name
        },
        isDefaultRender: true,

        widgetIsLoaded() {
            return true
        },

        init() {
            Serializer.addClass(this.name + 'value', [], null, 'itemvalue')
            Serializer.addProperty(this.name + 'value', {
                name: 'hsdu_state',
                displayName: 'HSDU State',
                // choices: ['primary', 'secondary', 'tertiary'],
                // default: 'primary',
                type: 'number',
                choices: [
                    {value: 1, text: "Accept updates"},
                    {value: 0, text: "Does not accept updates"},
                    {value: 2, text: "Unknow"}
                ],
                default: 2,
            })
            Serializer.addClass(this.name, [
                {
                    name: 'choices',
                    type: this.name + 'value[]',

                },
                {
                    category: 'Reporting & Dashboarding',
                    name: 'showTierInResponseList',
                    displayName: 'Show the state  question in the response list',
                    isRequired: false,
                    type: 'number'
                },
                {
                    category: 'Reporting & Dashboarding',
                    name: 'showFacilityInResponseList',
                    displayName: 'Show the state question in the facility list',
                    isRequired: false,
                    type: 'number',
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
        afterRender(question, el) {
        },

    }, 'customtype')
}
