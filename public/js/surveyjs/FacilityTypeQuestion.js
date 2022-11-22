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
        choices: ['primary', 'secondary', 'tertiary'],
        default: 'primary',
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
          name: 'showFacilityInResponseList',
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
