'use strict';
((Survey) =>
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
        // choices: ['primary', 'secondary', 'tertiary'],
        // default: 'primary',
        type: 'number',
        choices: [
          {value: 1, text: "Primary"},
          {value: 2, text: "Secondary"},
          {value: 3, text: "Tertiary"},
          { value: 4, text: "Other"},
          { value: 5, text: "Unknow"}
        ],
        default: 5,
      })
      Survey.Serializer.addClass(this.name, [
        {
          name: 'choices',
          type: this.name + 'value[]',

        },
        {
          category: 'Reporting & Dashboarding',
          name: 'showTierInResponseList',
          displayName: 'Show the derived tier question in the response list',
          isRequired: false,
          type: 'number',
        },
        {
          category: 'Reporting & Dashboarding',
          name: 'showTierInFacilityList',
          displayName: 'Show the derived tier question in the facility list',
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
    afterRender (question, el) {

    },

  }, 'customtype'))(Survey)
