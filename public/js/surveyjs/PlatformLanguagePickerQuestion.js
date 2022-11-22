'use strict';
((Survey) => Survey.ComponentCollection.Instance.add({
  iconName: 'icon-language',
  name: 'platformlanguagepicker',
  title: 'Platform language picker',
  category: 'HeRAMS',
  questionJSON: {
    type: 'checkbox',
    name: 'languages',
    title: 'Additional languages for this project, English is always enabled',
    choicesByUrl: {
      url: '/api-proxy/core/configuration/locales',
      valueName: 'locale',
      titleName: 'label',
    },
  },
  onInit () {
    // debugger;
  },

}))(Survey)
