'use strict';

((Survey) => Survey.ComponentCollection.Instance.add({
  iconName: 'icon-visible',
  name: 'projectvisibility',
  title: 'Project visibility picker',
  category: 'HeRAMS',
  questionJSON: {
    type: 'dropdown',
    name: 'projectvisibility',
    title: 'Project visibility',
    allowClear: false,
    required: true,
    choicesByUrl: {
      url: `/api-proxy/core/configuration/visibilities?_lang=${document.documentElement.lang}`
    }
  },
  onInit () {
    // debugger;
  }
}))(Survey)
